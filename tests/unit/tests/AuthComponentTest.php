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
        $user = $this->faker()->user();
        $app = $this->faker()->app();

        $result = $this->getAuth()->login($user, $app);
        $this->assertFalse($result->isSuccessful());
        $this->assertNotNull($result->getException());
        $this->assertEmpty($result->getErrors());
        $this->assertNull($result->getData());
    }

    public function testLoginWithPermission()
    {
        $app = $this->faker()->app();
        $permission = $this->faker()->permission('authenticate', $app);
        $role = $this->faker()->role('test', [$permission]);
        $user = $this->faker()->user($role);

        $result = $this->getAuth()->login($user, $app);
        $this->assertTrue($result->isSuccessful());
        $this->assertNull($result->getException());
        $this->assertEmpty($result->getErrors());
        $this->assertNotEmpty($result->getData());
        $this->assertNotEmpty($result->getData('tokens'));
        $this->assertNotNull($result->getData('tokens.access'));
        $this->assertNotNull($result->getData('tokens.refresh'));
    }

    public function testLogout()
    {
        $app = $this->faker()->app();
        $permission = $this->faker()->permission('authenticate', $app);
        $role = $this->faker()->role('test', [$permission]);
        $user = $this->faker()->user($role);

        $loginResult = $this->getAuth()->login($user, $app);
        $this->getAuth()->accessToken = $loginResult->getData('tokens.access');
        $logoutResult = $this->getAuth()->logout();
        $this->assertTrue($logoutResult->isSuccessful());
        $this->assertNull($logoutResult->getException());
        $this->assertEmpty($logoutResult->getErrors());
        $this->assertNull($logoutResult->getData());
    }

    public function testRefresh()
    {
        $app = $this->faker()->app();
        $permission = $this->faker()->permission('authenticate', $app);
        $role = $this->faker()->role('test', [$permission]);
        $user = $this->faker()->user($role);

        $loginResult = $this->getAuth()->login($user, $app);
        $refreshResult = $this->getAuth()->refresh($loginResult->getData('tokens.refresh'));
        $this->assertTrue($refreshResult->isSuccessful());
        $this->assertNull($refreshResult->getException());
        $this->assertEmpty($refreshResult->getErrors());
        $this->assertNotEmpty($refreshResult->getData());
        $this->assertNotEmpty($refreshResult->getData('tokens'));
        $this->assertNotNull($refreshResult->getData('tokens.access'));
        $this->assertNotNull($refreshResult->getData('tokens.refresh'));
    }

    public function testVerify()
    {
        $app = $this->faker()->app();
        $permission = $this->faker()->permission('authenticate', $app);
        $role = $this->faker()->role('test', [$permission]);
        $user = $this->faker()->user($role);

        $loginResult = $this->getAuth()->login($user, $app);
        $this->getAuth()->accessToken = $loginResult->getData('tokens.access');

        $this->assertTrue(
            $this->getAuth()->verify()
        );
    }

    public function testPayloadGetters()
    {
        $app = $this->faker()->app();
        $permission = $this->faker()->permission('authenticate', $app);
        $role = $this->faker()->role('test', [$permission]);
        $user = $this->faker()->user($role);

        $loginResult = $this->getAuth()->login($user, $app);
        $this->getAuth()->accessToken = $loginResult->getData('tokens.access');

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
