<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\forms\auth\LogoutForm;
use ddruganov\Yii2ApiAuth\forms\auth\RefreshForm;
use ddruganov\Yii2ApiAuth\http\filters\RbacFilter;
use ddruganov\Yii2ApiAuth\models\forms\LoginForm;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;
use yii\helpers\ArrayHelper;

final class AuthController extends SecureApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'auth' => [
                    'exceptions' => ['login', 'refresh']
                ],
                'rbac' => [
                    'class' => RbacFilter::class,
                    'rules' => ['logout' => 'authenticate'],
                    'exceptions' => ['login', 'refresh']
                ]
            ],
        );
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
            'logout' => [
                'class' => FormAction::class,
                'formClass' => LogoutForm::class
            ],
        ];
    }
}
