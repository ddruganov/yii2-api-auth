<?php

namespace tests\unit\tests;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use tests\components\MockAuthComponent;
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
        $this->getAuth()->setAccessToken($loginResult->getData('tokens.access'));
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
        $this->getAuth()->setAccessToken($loginResult->getData('tokens.access'));

        $this->assertTrue(
            $this->getAuth()->verify()
        );
    }

    public function testPayloadGetters()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $loginResult = $this->getAuth()->login($user, $app);
        $this->getAuth()->setAccessToken($loginResult->getData('tokens.access'));

        $this->assertNotNull(
            $this->getAuth()->getCurrentUser()
        );
        $this->assertNotNull(
            $this->getAuth()->getCurrentApp()
        );
    }

    private function getAuth(): MockAuthComponent
    {
        return Yii::$app->get(AuthComponentInterface::class);
    }
}
