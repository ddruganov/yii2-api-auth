<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\role;

use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class DeleteForm extends Form
{
    public ?int $id = null;

    public function rules()
    {
        return [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => Role::class, 'message' => 'Такой роли не существует'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = Role::findOne($this->id);
        if ($model->delete() === false) {
            return ExecutionResult::exception('Ошибка удаления роли');
        }

        $result = $this->deletePermissionBindings($model);
        if (!$result->isSuccessful()) {
            return $result;
        }

        $result = $this->deleteUserBindings($model);
        if (!$result->isSuccessful()) {
            return $result;
        }

        return ExecutionResult::success();
    }

    private function deletePermissionBindings(Role $role): ExecutionResult
    {
        $bindings = RoleHasPermission::find()
            ->byRoleId($role->getId())
            ->all();

        foreach ($bindings as $binding) {
            if ($binding->delete() === false) {
                return ExecutionResult::exception('Ошибка удаления привязки роли к разрешениям');
            }
        }

        return ExecutionResult::success();
    }

    private function deleteUserBindings(Role $role): ExecutionResult
    {
        $bindings = UserHasRole::find()
            ->byRoleId($role->getId())
            ->all();

        foreach ($bindings as $binding) {
            if ($binding->delete() === false) {
                return ExecutionResult::exception('Ошибка удаления привязки роли к пользователям');
            }
        }

        return ExecutionResult::success();
    }
}
