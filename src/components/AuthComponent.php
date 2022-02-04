<?php

namespace ddruganov\Yii2ApiAuth\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ddruganov\Yii2ApiAuth\models\token\AccessToken;
use ddruganov\Yii2ApiAuth\models\token\RefreshToken;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\DateHelper;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use Yii;
use yii\base\Component;

class AuthComponent extends Component
{
    public function login(User $user): ExecutionResult
    {
        if (!Yii::$app->get('rbac')->canAuthenticate($user)) {
            return ExecutionResult::exception('У вас нет прав на выполнение входа в систему');
        }

        $result = $this->createAccessToken($user);
        if (!$result->isSuccessful()) {
            return $result;
        }

        /** @var \ddruganov\Yii2ApiAuth\models\token\AccessToken */
        $accessToken = $result->getData('model');

        $result = $this->createRefreshToken($user, $accessToken);
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

    public function verify()
    {
        return $this->verifyAccessToken()->isSuccessful();
    }

    public function refresh(string $refreshToken): ExecutionResult
    {
        $refreshTokenModel = RefreshToken::findOne(['value' => $refreshToken]);
        if (!$refreshTokenModel) {
            return ExecutionResult::exception('Токен обновления недействителен');
        }

        if ($refreshTokenModel->isExpired()) {
            return ExecutionResult::exception('Срок годности токена обновления истёк');
        }

        if (!$refreshTokenModel->expire()) {
            return ExecutionResult::exception('Ошибка инвалидации токена обновления');
        }

        return $this->login($refreshTokenModel->getUser());
    }

    public function logout()
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
            return ExecutionResult::exception('Токен доступа недействителен');
        }

        if ($accessTokenModel->isExpired()) {
            return ExecutionResult::exception('Срок годности токена доступа истёк');
        }

        return ExecutionResult::success(['model' => $accessTokenModel]);
    }

    private function extractAccessTokenFromHeaders(): ?string
    {
        $accessToken = Yii::$app->getRequest()->getHeaders()->get('Authorization');
        $accessToken = str_replace('Bearer', '', $accessToken);
        return trim($accessToken);
    }

    public function getCurrentUser(): ?User
    {
        $jwt = $this->extractAccessTokenFromHeaders();
        $key = Yii::$app->params['authentication']['tokens']['secret'];
        $decoded = (array)JWT::decode($jwt, new Key($key, 'HS256'));
        $userId = $decoded['uid'];
        return User::findOne($userId);
    }

    private function createAccessToken(User $user): ExecutionResult
    {
        $issuedAt = DateHelper::now(null);
        $expiresAt = $issuedAt + Yii::$app->params['authentication']['tokens']['access']['ttl'];
        $payload = [
            'iss' => Yii::$app->params['authentication']['tokens']['access']['issuer'],
            'aud' => Yii::$app->params['authentication']['tokens']['access']['audience'],
            'iat' => $issuedAt,
            'uid' => $user->getId()
        ];

        $key = Yii::$app->params['authentication']['tokens']['secret'];
        $jwt = JWT::encode($payload, $key, 'HS256');

        $accessToken = new AccessToken([
            'value' => $jwt,
            'expires_at' => DateHelper::formatTimestamp('Y-m-d H:i:s', $expiresAt)
        ]);
        if (!$accessToken->save() || !$accessToken->refresh()) {
            return ExecutionResult::exception('Ошибка создания токена доступа');
        }

        return ExecutionResult::success(['model' => $accessToken]);
    }

    private function createRefreshToken(User $user, AccessToken $accessToken): ExecutionResult
    {
        $issuedAt = DateHelper::now(null);
        $expiresAt = $issuedAt + Yii::$app->params['authentication']['tokens']['refresh']['ttl'];
        $value = hash('sha256', $accessToken->getValue());

        $refreshToken = new RefreshToken([
            'user_id' => $user->getId(),
            'value' => $value,
            'access_token_id' => $accessToken->getId(),
            'expires_at' => DateHelper::formatTimestamp('Y-m-d H:i:s', $expiresAt)
        ]);
        if (!$refreshToken->save() || !$refreshToken->refresh()) {
            return ExecutionResult::exception('Ошибка создания токена обновления');
        }

        return ExecutionResult::success(['model' => $refreshToken]);
    }
}
