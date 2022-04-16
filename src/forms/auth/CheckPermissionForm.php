<?php

namespace ddruganov\Yii2ApiAuth\forms\auth;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\components\RbacComponentInterface;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;
use Yii;

class CheckPermissionForm extends Form
{
    public ?string $permissionName = null;

    public function rules()
    {
        return [
            [['permissionName'], 'required'],
            [['permissionName'], 'string'],
            [['permissionName'], 'exist', 'targetClass' => Permission::class, 'targetAttribute' => ['permissionName' => 'name'], 'message' => 'Такого разрешения не существует'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $success = Yii::$app->get(RbacComponentInterface::class)
            ->checkPermission(
                Permission::findOne(['name' => $this->permissionName]),
                Yii::$app->get(AuthComponentInterface::class)->getCurrentUser()
            );

        if (!$success) {
            return ExecutionResult::exception('Отказано в доступе');
        }

        return ExecutionResult::success();
    }
}
