<?php

namespace ddruganov\Yii2ApiAuth\forms\user;

use ddruganov\Yii2ApiAuth\models\User;

final class UpdateForm extends BaseForm
{
    public ?int $id = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => User::class, 'message' => 'Такого пользователя не существует'],
            [['email'], 'filter', 'filter' => function (string $email) {

                $existingUser = User::find()->byEmail($email)->one();
                if ($existingUser && $existingUser->getId() !== $this->id) {
                    $this->addError('email', 'Пользователь с таким email уже существует');
                }

                return $email;
            }]
        ]);
    }

    protected function getModel()
    {
        return User::findOne($this->id);
    }
}
