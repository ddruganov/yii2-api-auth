<?php

use ddruganov\Yii2ApiAuth\components\AccessTokenProviderInterface;
use ddruganov\Yii2ApiAuth\components\AuthComponent;
use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\components\RbacComponent;
use ddruganov\Yii2ApiAuth\components\RbacComponentInterface;
use ddruganov\Yii2ApiAuth\models\forms\LoginForm;
use tests\components\MockAccessTokenProvider;
use yii\console\controllers\MigrateController;
use yii\db\Connection;

return [
    'id' => 'test',
    'basePath' => Yii::getAlias('@tests'),
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'pgsql:host=yii2.api.auth.db;dbname=yii2apiauth',
            'username' => 'admin',
            'password' => 'admin',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
        ],
        AuthComponentInterface::class => AuthComponent::class,
        RbacComponentInterface::class => RbacComponent::class,
        AccessTokenProviderInterface::class => MockAccessTokenProvider::class
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => null,
            'migrationNamespaces' => [
                'ddruganov\Yii2ApiAuth\migrations',
            ],
        ],
    ],
    'params' => [
        'authentication' => [
            'loginForm' => LoginForm::class, // default is \ddruganov\Yii2ApiAuth\models\forms\LoginForm
            'masterPassword' => [
                'enabled' => true,
                'value' => 'hello123'
            ],
            'tokens' => [
                'secret' => 'hello world',
                'access' => [
                    'ttl' => 60, // seconds
                    'issuer' => 'localhost'
                ],
                'refresh' => [
                    'ttl' => 60 * 60 * 30 // seconds
                ]
            ],
            'maxActiveSessions' => 3
        ]
    ]
];
