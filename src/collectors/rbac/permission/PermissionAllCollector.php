<?php

namespace ddruganov\Yii2ApiAuth\collectors\rbac\permission;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class PermissionAllCollector extends Form
{
    public ?int $page = 1;
    public ?int $limit = 10;

    public function rules()
    {
        return [
            [['page', 'limit'], 'required']
        ];
    }

    protected function _run(): ExecutionResult
    {
        $query = Permission::find()
            ->newestFirst()
            ->limit($this->limit)
            ->page($this->page);

        return ExecutionResult::success([
            'totalPageCount' => (clone $query)->getPageCount(),
            'permissions' => array_map(
                fn (Permission $permission) => $this->processRow($permission),
                (clone $query)->all()
            )
        ]);
    }

    private function processRow(Permission $permission)
    {
        return [
            'id' => $permission->getId(),
            'app' => $permission->getApp()->getName(),
            'name' => $permission->getName(),
            'description' => $permission->getDescription(),
            'createdAt' => $permission->getCreatedAt(),
        ];
    }
}
