<?php

namespace tests\unit\tests\rbac\permission;

use ddruganov\Yii2ApiAuth\forms\rbac\permission\DeleteForm;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
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
        $form = new DeleteForm(['id' => $this->getFaker()->permission()->getId()]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
    }

    public function testValidWithRoleBindings()
    {
        $permission = $this->getFaker()->permission();
        $role = $this->getFaker()->role(permissionIds: [$permission->getId()]);

        $form = new DeleteForm(['id' => $permission->getId()]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);

        $this->assertFalse(
            RoleHasPermission::find()
                ->byPermissionId($permission->getId())
                ->byRoleId($role->getId())
                ->exists()
        );
    }
}
