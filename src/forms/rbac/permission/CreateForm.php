<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\permission;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;

final class CreateForm extends BaseForm
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'filter', 'filter' => function (string $value) {

                $permissionExists = Permission::find()
                    ->byName($this->name)
                    ->byAppUuid($this->appUuid)
                    ->exists();
                if ($permissionExists) {
                    $message = 'Разрешение с такими параметрами уже существует';
                    $this->addError('name', $message);
                    $this->addError('appUuid', $message);
                }

                return $value;
            }],
        ]);
    }
}
