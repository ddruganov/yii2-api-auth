<?php

namespace ddruganov\Yii2ApiAuth\forms\auth;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\models\token\RefreshToken;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;
use Yii;

class RefreshForm extends Form
{
    public ?string $refreshToken = null;

    public function rules()
    {
        return [
            [['refreshToken'], 'required'],
            [['refreshToken'], 'string'],
            [['refreshToken'], 'exist', 'targetClass' => RefreshToken::class, 'targetAttribute' => ['refreshToken' => 'value'], 'message' => 'Такого токена обновления не существует'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        return Yii::$app->get(AuthComponentInterface::class)->refresh($this->refreshToken);
    }
}
