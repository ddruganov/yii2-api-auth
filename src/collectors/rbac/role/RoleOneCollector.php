<?php

namespace ddruganov\Yii2ApiAuth\collectors\rbac\role;

use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class RoleOneCollector extends Form
{
    public ?int $id = null;

    public function rules()
    {
        return [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => Role::class, 'message' => 'Роль не найдена'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $role = Role::findOne($this->id);

        return ExecutionResult::success([
            'id' => $role->getId(),
            'name' => $role->getName(),
            'description' => $role->getDescription(),
            'permissionIds' => RoleHasPermission::find()
                ->select(['permission_id'])
                ->byRoleId($role->getId())
                ->asArray()
                ->column()
        ]);
    }
}
