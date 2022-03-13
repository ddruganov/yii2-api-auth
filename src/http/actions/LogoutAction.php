<?php

namespace ddruganov\Yii2ApiAuth\http\actions;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\http\actions\ApiAction;
use Yii;

class LogoutAction extends ApiAction
{
    public function run(): ExecutionResult
    {
        return Yii::$app->get(AuthComponentInterface::class)->logout();
    }
}
