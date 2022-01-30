<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\http\actions\LoginAction;
use ddruganov\Yii2ApiAuth\http\actions\LogoutAction;
use ddruganov\Yii2ApiAuth\http\actions\RefreshAction;
use ddruganov\Yii2ApiAuth\http\filters\RbacFilter;

class AuthController extends SecureApiController
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'rbac' => [
                    'class' => RbacFilter::class,
                    'rules' => [
                        'login' => 'authenticate',
                        'refresh' => 'authenticate',
                        'logout' => 'authenticate',
                    ]
                ]
            ]
        );
    }

    public function actions()
    {
        return [
            'login' => LoginAction::class,
            'refresh' => RefreshAction::class,
            'logout' => LogoutAction::class,
        ];
    }
}
