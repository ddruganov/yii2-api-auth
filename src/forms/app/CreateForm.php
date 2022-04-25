<?php

namespace ddruganov\Yii2ApiAuth\forms\app;

use ddruganov\Yii2ApiAuth\models\App;

final class CreateForm extends BaseForm
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['alias'], 'unique', 'targetClass' => App::class, 'message' => 'Такое приложение уже существует'],
        ]);
    }
}
