<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\collectors\user\CurrentUserCollector;
use ddruganov\Yii2ApiAuth\forms\auth\CheckPermissionForm;
use ddruganov\Yii2ApiAuth\forms\auth\LoginForm;
use ddruganov\Yii2ApiAuth\forms\auth\LogoutForm;
use ddruganov\Yii2ApiAuth\forms\auth\RefreshForm;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\http\actions\ClosureAction;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;
use yii\helpers\ArrayHelper;

class AuthController extends SecureApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'auth' => [
                'exceptions' => ['login', 'refresh', 'verify']
            ],
            'rbac' => [
                'rules' => [
                    'current-user' => 'authenticate',
                    'logout' => 'authenticate',
                    'verify' => 'authenticate',
                    'check-permission' => 'authenticate',
                ],
                'exceptions' => ['login', 'refresh']
            ]
        ]);
    }

    public function actions()
    {
        return [
            'login' => [
                'class' => FormAction::class,
                'formClass' => LoginForm::class
            ],
            'refresh' => [
                'class' => FormAction::class,
                'formClass' => RefreshForm::class
            ],
            'current-user' => [
                'class' => FormAction::class,
                'formClass' => CurrentUserCollector::class
            ],
            'logout' => [
                'class' => FormAction::class,
                'formClass' => LogoutForm::class
            ],
            'verify' => [
                'class' => ClosureAction::class,
                'closure' => fn () => ExecutionResult::success()
            ],
            'check-permission' => [
                'class' => FormAction::class,
                'formClass' => CheckPermissionForm::class
            ]
        ];
    }
}
