<?php

namespace tests\unit\tests;

use ddruganov\Yii2ApiAuth\components\RbacComponentInterface;
use tests\unit\BaseUnitTest;
use Yii;

final class RbacComponentTest extends BaseUnitTest
{
    public function testWithoutPermission()
    {
        $permission = $this->faker()->permission(
            $this->faker()->asciify(),
            $this->faker()->app()
        );
        $user = $this->faker()->user();
        $result = $this->getRbac()->checkPermission($permission, $user);
        $this->assertFalse($result);
    }

    public function testWithPermission()
    {
        $permission = $this->faker()->permission(
            $this->faker()->asciify(),
            $this->faker()->app()
        );
        $role = $this->faker()->role('test', [$permission]);
        $user = $this->faker()->user($role);
        $result = $this->getRbac()->checkPermission($permission, $user);
        $this->assertTrue($result);
    }

    public function testCannotAuthenticate()
    {
        $app = $this->faker()->app();
        $user = $this->faker()->user();
        $result = $this->getRbac()->canAuthenticate($user, $app);
        $this->assertFalse($result);
    }

    public function testCanAuthenticate()
    {
        $app = $this->faker()->app();
        $permission = $this->faker()->permission('authenticate', $app);
        $role = $this->faker()->role('test', [$permission]);
        $user = $this->faker()->user($role);

        $result = $this->getRbac()->canAuthenticate($user, $app);
        $this->assertTrue($result);
    }

    private function getRbac(): RbacComponentInterface
    {
        return Yii::$app->get(RbacComponentInterface::class);
    }
}
