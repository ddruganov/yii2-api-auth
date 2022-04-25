<?php

namespace tests\unit\tests\auth;

use ddruganov\Yii2ApiAuth\components\RbacComponentInterface;
use tests\unit\BaseUnitTest;
use Yii;

final class RbacComponentTest extends BaseUnitTest
{
    public function testWithoutPermission()
    {
        $permission = $this->getFaker()->permission(
            $this->getFaker()->permissionName(),
            $this->getFaker()->app()
        );
        $user = $this->getFaker()->user();
        $result = $this->getRbac()->checkPermission($permission, $user);
        $this->assertFalse($result);
    }

    public function testWithPermission()
    {
        $permission = $this->getFaker()->permission(
            $this->getFaker()->permissionName(),
            $this->getFaker()->app()
        );
        $role = $this->getFaker()->role('test', [$permission->getId()]);
        $user = $this->getFaker()->user($role);
        $result = $this->getRbac()->checkPermission($permission, $user);
        $this->assertTrue($result);
    }

    public function testCannotAuthenticate()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->user();
        $result = $this->getRbac()->canAuthenticate($user, $app);
        $this->assertFalse($result);
    }

    public function testCanAuthenticate()
    {
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);

        $result = $this->getRbac()->canAuthenticate($user, $app);
        $this->assertTrue($result);
    }

    private function getRbac(): RbacComponentInterface
    {
        return Yii::$app->get(RbacComponentInterface::class);
    }
}
