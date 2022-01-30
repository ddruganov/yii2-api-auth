<?php

namespace ddruganov\Yii2ApiAuth\http\actions;

use ddruganov\Yii2ApiAuth\models\forms\LoginForm;
use ddruganov\Yii2ApiEssentials\common\ExecutionResult;
use ddruganov\Yii2ApiEssentials\common\http\actions\ApiAction;
use Yii;

class LoginAction extends ApiAction
{
    public function run(): ExecutionResult
    {
        $loginForm = $this->getLoginForm();
        $loginForm->setAttributes($this->getData());
        if (!$loginForm->validate()) {
            return ExecutionResult::failure($loginForm->getFirstErrors());
        }

        return Yii::$app->get('auth')->login($loginForm->getUser());
    }

    private function getLoginForm(): LoginForm
    {
        $loginFormClass = Yii::$app->params['authentication']['loginForm'] ?? LoginForm::class;
        return new $loginFormClass;
    }
}
