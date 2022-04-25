<?php

namespace api\controllers;

use ddruganov\Yii2ApiAuth\collectors\user\UserAllCollector;
use ddruganov\Yii2ApiAuth\collectors\user\UserOneCollector;
use ddruganov\Yii2ApiAuth\forms\user\CreateForm;
use ddruganov\Yii2ApiAuth\forms\user\DeleteForm;
use ddruganov\Yii2ApiAuth\forms\user\UpdateForm;
use ddruganov\Yii2ApiAuth\http\controllers\SecureApiController;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;
use yii\helpers\ArrayHelper;

class UserController extends SecureApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'rbac' => [
                'rules' => [
                    'all' => 'user.view',
                    'one' => 'user.view',
                    'create' => 'user.create',
                    'update' => 'user.update',
                    'delete' => 'user.delete',
                ]
            ]
        ]);
    }

    public function actions()
    {
        return [
            'all' => [
                'class' => FormAction::class,
                'formClass' => UserAllCollector::class
            ],
            'one' => [
                'class' => FormAction::class,
                'formClass' => UserOneCollector::class
            ],
            'create' => [
                'class' => FormAction::class,
                'formClass' => CreateForm::class
            ],
            'update' => [
                'class' => FormAction::class,
                'formClass' => UpdateForm::class
            ],
            'delete' => [
                'class' => FormAction::class,
                'formClass' => DeleteForm::class
            ],
        ];
    }
}
