<?php

namespace ddruganov\Yii2ApiAuth\collectors\rbac\permission;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class PermissionOneCollector extends Form
{
    public ?int $id = null;

    public function rules()
    {
        return [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => Permission::class, 'message' => 'Разрешение не найдено'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $permission = Permission::findOne($this->id);

        return ExecutionResult::success([
            'id' => $permission->getId(),
            'appUuid' => $permission->getAppUuid(),
            'name' => $permission->getName(),
            'description' => $permission->getDescription()
        ]);
    }
}
