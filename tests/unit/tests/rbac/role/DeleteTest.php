<?php

namespace tests\unit\tests\rbac\role;

use ddruganov\Yii2ApiAuth\forms\rbac\role\DeleteForm;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use tests\unit\BaseUnitTest;

final class DeleteTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new DeleteForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['id']
        );
    }

    public function testValid()
    {
        $form = new DeleteForm(['id' => $this->getFaker()->role()->getId()]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
    }

    public function testValidWithPermissionBindings()
    {
        $permission = $this->getFaker()->permission();
        $role = $this->getFaker()->role(permissionIds: [$permission->getId()]);

        $form = new DeleteForm(['id' => $role->getId()]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);

        $this->assertFalse(
            RoleHasPermission::find()
                ->byRoleId($role->getId())
                ->byPermissionId($permission->getId())
                ->exists()
        );
    }

    public function testValidWithUserBindings()
    {
        $role = $this->getFaker()->role();
        $user = $this->getFaker()->user($role);

        $form = new DeleteForm(['id' => $role->getId()]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);

        $this->assertFalse(
            UserHasRole::find()
                ->byUserId($user->getId())
                ->byRoleId($role->getId())
                ->exists()
        );
    }
}
