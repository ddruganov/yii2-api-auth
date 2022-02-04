<?php

namespace ddruganov\Yii2ApiAuth\http\filters;

use ddruganov\Yii2ApiAuth\exceptions\UnauthenticatedException;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use Throwable;
use Yii;
use yii\base\ActionFilter;

class AuthFilter extends ActionFilter
{
    public array $exceptions = [];

    public function beforeAction($action)
    {
        if (in_array(Yii::$app->controller->action->id, $this->exceptions)) {
            return parent::beforeAction($action);
        }

        try {
            if (!Yii::$app->get('auth')->verify()) {
                throw new UnauthenticatedException();
            }
        } catch (Throwable $t) {
            $isUnauthenticatedException = $t instanceof UnauthenticatedException;
            if (!$isUnauthenticatedException) {
                throw $t;
            }

            Yii::$app->getResponse()->data = ExecutionResult::exception($t->getMessage());
            Yii::$app->getResponse()->setStatusCode(401);
            Yii::$app->end();
        }

        return parent::beforeAction($action);
    }
}
