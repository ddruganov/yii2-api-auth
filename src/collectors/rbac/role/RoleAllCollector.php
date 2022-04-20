<?php

namespace ddruganov\Yii2ApiAuth\collectors\rbac\role;

use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class RoleAllCollector extends Form
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
        $query = Role::find()
            ->newestFirst()
            ->limit($this->limit)
            ->page($this->page);

        return ExecutionResult::success([
            'totalPageCount' => (clone $query)->getPageCount(),
            'roles' => array_map(
                fn (Role $role) => $this->processRow($role),
                (clone $query)->all()
            )
        ]);
    }

    private function processRow(Role $role)
    {
        return [
            'id' => $role->getId(),
            'name' => $role->getName(),
            'description' => $role->getDescription(),
            'createdAt' => $role->getCreatedAt(),
        ];
    }
}
