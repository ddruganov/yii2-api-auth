<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\role;

use ddruganov\Yii2ApiAuth\models\rbac\Role;

final class UpdateForm extends BaseForm
{
    public ?int $id = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => Role::class, 'message' => 'Такой роли не существует'],
            [['name'], 'filter', 'filter' => function (string $name) {

                $existingRole = Role::find()->byName($name)->one();
                if ($existingRole && $existingRole->getId() !== $this->id) {
                    $this->addError('name', 'Роль с таким названием уже существует');
                }

                return $name;
            }]
        ]);
    }

    protected function getModel()
    {
        return Role::findOne($this->id);
    }
}
