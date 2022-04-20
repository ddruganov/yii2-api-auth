<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\role;

use ddruganov\Yii2ApiAuth\models\rbac\Role;

final class CreateForm extends BaseForm
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'unique', 'targetClass' => Role::class, 'message' => 'Роль с таким названием уже существует'],
        ]);
    }
}
