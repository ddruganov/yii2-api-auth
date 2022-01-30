<?php

namespace ddruganov\Yii2ApiAuth\http\actions;

use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\http\actions\ApiAction;
use Yii;

class RefreshAction extends ApiAction
{
    public function run(): ExecutionResult
    {
        $refreshToken = $this->getData('refreshToken');

        return Yii::$app->get('auth')->refresh($refreshToken);
    }
}
