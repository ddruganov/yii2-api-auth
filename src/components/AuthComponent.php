<?php

namespace ddruganov\Yii2ApiAuth\components;

use ddruganov\Yii2ApiAuth\models\App;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ddruganov\Yii2ApiAuth\models\token\AccessToken;
use ddruganov\Yii2ApiAuth\models\token\RefreshToken;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\DateHelper;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use Yii;
use yii\base\Component;

class AuthComponent extends Component implements AuthComponentInterface
{
    public function login(User $user, App $app): ExecutionResult
    {
        if (!Yii::$app->get(RbacComponentInterface::class)->canAuthenticate($user, $app)) {
            return ExecutionResult::exception('У вас нет прав на выполнение входа в систему');
        }

        $result = $this->expirePreviousSessions($user, $app);
        if (!$result->isSuccessful()) {
            return $result;
        }

        $result = $this->createAccessToken($user, $app);
        if (!$result->isSuccessful()) {
            return $result;
        }

        /** @var \ddruganov\Yii2ApiAuth\models\token\AccessToken */
        $accessToken = $result->getData('model');

        $result = $this->createRefreshToken($user, $app, $accessToken);
        if (!$result->isSuccessful()) {
            return $result;
        }

        /** @var \ddruganov\Yii2ApiAuth\models\token\RefreshToken */
        $refreshToken = $result->getData('model');

        return ExecutionResult::success([
            'tokens' => [
                'access' => $accessToken->getValue(),
                'refresh' => $refreshToken->getValue()
            ]
        ]);
    }

    private function expirePreviousSessions(User $user, App $app): ExecutionResult
    {
        $refreshTokenQuery = RefreshToken::find()
            ->expired(false)
            ->byUserId($user->getId())
            ->byAppUuid($app->getUuid());

        $activeRefreshTokenCount = (clone $refreshTokenQuery)->count();
        $maxAllowedCount = Yii::$app->params['authentication']['maxActiveSessions'] - 1; // subtract one because we havent yet authenticated the user with a new request

        if ($activeRefreshTokenCount <= $maxAllowedCount) {
            return ExecutionResult::success();
        }

        $oldRefreshTokens = (clone $refreshTokenQuery)
            ->oldestFirst()
            ->limit($activeRefreshTokenCount - $maxAllowedCount)
            ->all();
        foreach ($oldRefreshTokens as $oldRefreshToken) {
            if ($oldRefreshToken->expire() === false) {
                return ExecutionResult::exception('Ошибка деактивации предыдущих сессий');
            }
        }

        return ExecutionResult::success();
    }

    public function verify(): bool
    {
        return $this->verifyAccessToken()->isSuccessful();
    }

    public function refresh(string $refreshToken): ExecutionResult
    {
        $refreshTokenModel = RefreshToken::findOne(['value' => $refreshToken]);
        if (!$refreshTokenModel) {
            return ExecutionResult::failure(['refreshToken' => 'Токен обновления недействителен']);
        }

        if ($refreshTokenModel->isExpired()) {
            return ExecutionResult::failure(['refreshToken' => 'Срок годности токена обновления истёк']);
        }

        if (!$refreshTokenModel->expire()) {
            return ExecutionResult::exception('Ошибка инвалидации токена обновления');
        }

        return $this->login($refreshTokenModel->getUser(), $refreshTokenModel->getApp());
    }

    public function logout(): ExecutionResult
    {
        $accessTokenVerificationResult = $this->verifyAccessToken();
        if (!$accessTokenVerificationResult->isSuccessful()) {
            return $accessTokenVerificationResult;
        }

        $refreshTokenModel = RefreshToken::findOne(['access_token_id' => $accessTokenVerificationResult->getData('model')->getId()]);
        if (!$refreshTokenModel->expire()) {
            return ExecutionResult::exception('Ошибка инвалидации пары токенов');
        }

        return ExecutionResult::success();
    }

    private function verifyAccessToken(): ExecutionResult
    {
        $accessToken = $this->extractAccessTokenFromHeaders();
        $accessTokenModel = AccessToken::findOne(['value' => $accessToken]);
        if (!$accessTokenModel) {
            return ExecutionResult::failure(['accessToken' => 'Токен доступа недействителен']);
        }

        if ($accessTokenModel->isExpired()) {
            return ExecutionResult::failure(['accessToken' => 'Срок годности токена доступа истёк']);
        }

        return ExecutionResult::success(['model' => $accessTokenModel]);
    }

    protected function extractAccessTokenFromHeaders(): ?string
    {
        $accessToken = Yii::$app->getRequest()->getHeaders()->get('Authorization');
        $accessToken = str_replace('Bearer', '', $accessToken);
        return trim($accessToken);
    }

    public function getCurrentUser(): ?User
    {
        return User::findOne($this->getPayloadValue('uid'));
    }

    public function getCurrentApp(): ?App
    {
        return App::findOne($this->getPayloadValue('auuid'));
    }

    public function getPayloadValue(string $key, mixed $default = null): mixed
    {
        $jwt = $this->extractAccessTokenFromHeaders();
        $secret = Yii::$app->params['authentication']['tokens']['secret'];
        $decoded = (array)JWT::decode($jwt, new Key($secret, 'HS256'));
        return $decoded[$key] ?? $default;
    }

    private function createAccessToken(User $user, App $app): ExecutionResult
    {
        $issuedAt = DateHelper::now(null);
        $expiresAt = $issuedAt + Yii::$app->params['authentication']['tokens']['access']['ttl'];
        $payload = [
            'iss' => Yii::$app->params['authentication']['tokens']['access']['issuer'],
            'aud' => $app->getAudience(),
            'iat' => $issuedAt,
            'uid' => $user->getId(),
            'auuid' => $app->getUuid()
        ];

        $key = Yii::$app->params['authentication']['tokens']['secret'];
        $jwt = JWT::encode($payload, $key, 'HS256');

        $accessToken = new AccessToken([
            'value' => $jwt,
            'expires_at' => DateHelper::formatTimestamp('Y-m-d H:i:s', $expiresAt)
        ]);
        if (!$accessToken->save()) {
            return ExecutionResult::exception('Ошибка создания токена доступа');
        }

        return ExecutionResult::success(['model' => $accessToken]);
    }

    private function createRefreshToken(User $user, App $app, AccessToken $accessToken): ExecutionResult
    {
        $issuedAt = DateHelper::now(null);
        $expiresAt = $issuedAt + Yii::$app->params['authentication']['tokens']['refresh']['ttl'];
        $value = hash('sha256', $accessToken->getValue());

        $refreshToken = new RefreshToken([
            'user_id' => $user->getId(),
            'app_uuid' => $app->getUuid(),
            'value' => $value,
            'access_token_id' => $accessToken->getId(),
            'expires_at' => DateHelper::formatTimestamp('Y-m-d H:i:s', $expiresAt)
        ]);
        if (!$refreshToken->save()) {
            return ExecutionResult::exception('Ошибка создания токена обновления');
        }

        return ExecutionResult::success(['model' => $refreshToken]);
    }
}
