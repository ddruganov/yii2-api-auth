<?php

namespace ddruganov\Yii2ApiAuth\http\filters;

use ddruganov\Yii2ApiEssentials\ExecutionResult;
use Yii;
use yii\base\ActionFilter;

class AuthFilter extends ActionFilter
{
    public array $exceptions = [];

    public function beforeAction($action)
    {
        if (in_array(Yii::$app->controller->action->id, $this->except)) {
            return parent::beforeAction($action);
        }

        if (Yii::$app->get('auth')->verify()) {
            return parent::beforeAction($action);
        }

        Yii::$app->getResponse()->data = ExecutionResult::exception('Ваша авторизация недействительна');
        Yii::$app->getResponse()->setStatusCode(401);
        Yii::$app->end();
    }
}
