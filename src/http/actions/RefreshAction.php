<?php

namespace ddruganov\Yii2ApiAuth\http\actions;

use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\http\actions\ApiAction;
use Yii;

class RefreshAction extends ApiAction
{
    public function run(): ExecutionResult
    {
        $refreshToken = $this->getData('refreshToken', '');

        $result = Yii::$app->get('auth')->refresh($refreshToken);

        !$result->isSuccessful() && Yii::$app->getResponse()->setStatusCode(401);

        return $result;
    }
}
