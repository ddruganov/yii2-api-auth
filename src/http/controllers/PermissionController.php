<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\collectors\rbac\permission\PermissionAllCollector;
use ddruganov\Yii2ApiAuth\collectors\rbac\permission\PermissionOneCollector;
use ddruganov\Yii2ApiAuth\forms\rbac\permission\CreateForm;
use ddruganov\Yii2ApiAuth\forms\rbac\permission\DeleteForm;
use ddruganov\Yii2ApiAuth\forms\rbac\permission\UpdateForm;
use ddruganov\Yii2ApiAuth\http\controllers\SecureApiController;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;
use yii\helpers\ArrayHelper;

final class PermissionController extends SecureApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'rbac' => [
                'rules' => [
                    'all' => 'permission.view',
                    'one' => 'permission.view',
                    'create' => 'permission.create',
                    'update' => 'permission.update',
                    'delete' => 'permission.delete',
                ]
            ]
        ]);
    }

    public function actions()
    {
        return [
            'all' => [
                'class' => FormAction::class,
                'formClass' => PermissionAllCollector::class
            ],
            'one' => [
                'class' => FormAction::class,
                'formClass' => PermissionOneCollector::class
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
