<?php

namespace ddruganov\Yii2ApiAuth\forms\user;

use ddruganov\Yii2ApiAuth\models\User;
use Yii;
use yii\base\Model;

class CreateForm extends BaseForm
{
    public ?string $password = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['email'], 'unique', 'targetClass' => User::class, 'message' => 'Такой пользователь уже существует'],
            [['password'], 'required', 'message' => 'Поле обязательно для заполнения'],
        ]);
    }

    protected function setCustomAttributes(Model $model)
    {
        $model->setAttributes([
            'password' => Yii::$app->getSecurity()->generatePasswordHash($this->password)
        ]);
    }
}
