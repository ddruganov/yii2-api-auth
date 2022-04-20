<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\role;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

abstract class BaseForm extends Form
{
    public ?string $name = null;
    public ?string $description = null;
    public ?array $permissionIds = null;

    public function rules()
    {
        return [
            [['name', 'description'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['name'], 'match', 'pattern' => '/^[a-z]+$/', 'message' => 'Неверный формат названия'],
            [['permissionIds'], 'each', 'rule' => ['integer']],
            [['permissionIds'], 'each', 'rule' => ['exist', 'targetClass' => Permission::class, 'targetAttribute' => ['permissionIds' => 'id']]],
            [['permissionIds'], 'default', 'value' => []]
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = $this->getModel();
        $model->setAttributes([
            'name' => $this->name,
            'description' => $this->description
        ]);
        if (!$model->save()) {
            return ExecutionResult::exception('Ошибка сохранения модели');
        }

        $result = $this->bindPermissions($model);
        if (!$result->isSuccessful()) {
            return $result;
        }

        return ExecutionResult::success([
            'id' => $model->getId()
        ]);
    }

    protected function getModel()
    {
        return new Role();
    }

    private function bindPermissions(Role $role): ExecutionResult
    {
        $bindings = RoleHasPermission::find()->byRoleId($role->getId())->all();
        foreach ($bindings as $binding) {
            if ($binding->delete() === false) {
                return ExecutionResult::exception('Ошибка удаления привязок к разрешениям');
            }
        }

        foreach ($this->permissionIds as $permissionId) {
            $binding = new RoleHasPermission([
                'role_id' => $role->getId(),
                'permission_id' => $permissionId
            ]);
            if (!$binding->save()) {
                return ExecutionResult::exception('Ошибка привязки разрешений');
            }
        }

        return ExecutionResult::success();
    }
}
