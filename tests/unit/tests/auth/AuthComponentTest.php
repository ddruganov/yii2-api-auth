<?php

namespace tests\unit\tests\auth;

use ddruganov\Yii2ApiAuth\components\AccessTokenProviderInterface;
use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\models\token\RefreshToken;
use tests\components\MockAccessTokenProvider;
use tests\unit\BaseUnitTest;
use Yii;

final class AuthComponentTest extends BaseUnitTest
{
    public function testLoginWithoutPermission()
    {
        $user = $this->getFaker()->user();
        $app = $this->getFaker()->app();

        $result = $this->getAuth()->login($user, $app);
        $this->assertExecutionResultException($result);
    }

    public function testLoginWithPermission()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $result = $this->getAuth()->login($user, $app);
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotNull($result->getData('tokens.access'));
        $this->assertNotNull($result->getData('tokens.refresh'));
    }

    public function testLogout()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $loginResult = $this->getAuth()->login($user, $app);
        $this->getAccessTokenProvider()->setAccessToken($loginResult->getData('tokens.access'));
        $logoutResult = $this->getAuth()->logout();
        $this->assertExecutionResultSuccessful($logoutResult);
        $this->assertNull($logoutResult->getData());
    }

    public function testRefresh()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $loginResult = $this->getAuth()->login($user, $app);
        $refreshResult = $this->getAuth()->refresh($loginResult->getData('tokens.refresh'));
        $this->assertExecutionResultSuccessful($refreshResult);
        $this->assertNotNull($refreshResult->getData('tokens.access'));
        $this->assertNotNull($refreshResult->getData('tokens.refresh'));
    }

    public function testVerify()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $loginResult = $this->getAuth()->login($user, $app);
        $this->getAccessTokenProvider()->setAccessToken($loginResult->getData('tokens.access'));

        $this->assertTrue(
            $this->getAuth()->verify()
        );
    }

    public function testPayloadGetters()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $loginResult = $this->getAuth()->login($user, $app);
        $this->getAccessTokenProvider()->setAccessToken($loginResult->getData('tokens.access'));

        $this->assertNotNull(
            $this->getAuth()->getCurrentUser()
        );
        $this->assertNotNull(
            $this->getAuth()->getCurrentApp()
        );
    }

    public function testMaxAllowedSessions()
    {
        $maxAllowedSessions = Yii::$app->params['authentication']['maxActiveSessions'];
        $sessionsToCreate = $maxAllowedSessions + $this->getFaker()->numberBetween(1, 3);

        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $refreshTokenCountQuery = RefreshToken::find()
            ->expired(false)
            ->byUserId($user->getId())
            ->byAppUuid($app->getUuid());

        for ($index = 1; $index <= $sessionsToCreate; $index++) {
            $this->getAuth()->login($user, $app);

            $activeRefreshTokenCount = (clone $refreshTokenCountQuery)->count();

            $countShouldBe = $index <= $maxAllowedSessions
                ? $index
                : $maxAllowedSessions;

            $this->assertTrue($activeRefreshTokenCount === $countShouldBe);
        }
    }

    private function getAuth(): AuthComponentInterface
    {
        return Yii::$app->get(AuthComponentInterface::class);
    }

    private function getAccessTokenProvider(): MockAccessTokenProvider
    {
        return Yii::$app->get(AccessTokenProviderInterface::class);
    }
}
