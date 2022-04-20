<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\collectors\rbac\permission\PermissionAllCollector;
use ddruganov\Yii2ApiAuth\collectors\rbac\permission\PermissionOneCollector;
use ddruganov\Yii2ApiAuth\http\controllers\SecureApiController;
use ddruganov\Yii2ApiAuth\http\filters\RbacFilter;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;

final class PermissionController extends SecureApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'rbac' => [
                'class' => RbacFilter::class,
                'rules' => [
                    'all' => 'permission.view',
                    'one' => 'permission.view',
                    'create' => 'permission.create',
                    'update' => 'permission.edit',
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
