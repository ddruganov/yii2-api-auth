<?php

namespace tests\unit\tests\user;

use ddruganov\Yii2ApiAuth\forms\user\DeleteForm;
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
        $form = new DeleteForm(['id' => $this->getFaker()->user()->getId()]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
    }

    public function testValidWithRoleBindings()
    {
        $permission = $this->getFaker()->permission();
        $role = $this->getFaker()->role(permissionIds: [$permission->getId()]);
        $user = $this->getFaker()->user($role);

        $form = new DeleteForm(['id' => $user->getId()]);
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
