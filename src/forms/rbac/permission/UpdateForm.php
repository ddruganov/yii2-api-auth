<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\permission;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;

final class UpdateForm extends BaseForm
{
    public ?int $id = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => Permission::class, 'message' => 'Такого разрешения не существует'],
            [['name'], 'filter', 'filter' => function (string $name) {

                $existingPermission = Permission::find()
                    ->byName($this->name)
                    ->byAppUuid($this->appUuid)
                    ->one();
                if ($existingPermission && $existingPermission->getId() !== $this->id) {
                    $message = 'Разрешение с такими параметрами уже существует';
                    $this->addError('name', $message);
                    $this->addError('appUuid', $message);
                }

                return $name;
            }]
        ]);
    }

    protected function getModel()
    {
        return Permission::findOne($this->id);
    }
}
