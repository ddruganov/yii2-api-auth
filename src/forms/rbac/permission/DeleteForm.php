<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\permission;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class DeleteForm extends Form
{
    public ?int $id = null;

    public function rules()
    {
        return [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => Permission::class, 'message' => 'Такого разрешения не существует']
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = Permission::findOne($this->id);
        if ($model->delete() === false) {
            return ExecutionResult::exception('Ошибка удаления разрешения');
        }

        $result = $this->deleteRoleBindings($model);
        if (!$result->isSuccessful()) {
            return $result;
        }

        return ExecutionResult::success();
    }

    private function deleteRoleBindings(Permission $permission): ExecutionResult
    {
        $bindings = RoleHasPermission::find()
            ->byPermissionId($permission->getId())
            ->all();

        foreach ($bindings as $binding) {
            if ($binding->delete() === false) {
                return ExecutionResult::exception('Ошибка удаления привязки разрешения к ролям');
            }
        }

        return ExecutionResult::success();
    }
}
