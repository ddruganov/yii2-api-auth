<?php

namespace ddruganov\Yii2ApiAuth\forms\auth;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;
use Yii;

class LogoutForm extends Form
{
    protected function _run(): ExecutionResult
    {
        return Yii::$app->get(AuthComponentInterface::class)->logout();
    }
}
