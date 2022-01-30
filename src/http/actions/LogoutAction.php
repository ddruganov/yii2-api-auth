<?php

namespace ddruganov\Yii2ApiAuth\http\actions;

use ddruganov\Yii2ApiEssentials\common\ExecutionResult;
use ddruganov\Yii2ApiEssentials\common\http\actions\ApiAction;
use Yii;

class LogoutAction extends ApiAction
{
    public function run(): ExecutionResult
    {
        return Yii::$app->get('auth')->logout();
    }
}
