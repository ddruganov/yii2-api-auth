<?php

namespace api\controllers;

use ddruganov\Yii2ApiAuth\collectors\rbac\role\RoleAllCollector;
use ddruganov\Yii2ApiAuth\collectors\rbac\role\RoleOneCollector;
use ddruganov\Yii2ApiAuth\forms\rbac\role\CreateForm;
use ddruganov\Yii2ApiAuth\forms\rbac\role\DeleteForm;
use ddruganov\Yii2ApiAuth\forms\rbac\role\UpdateForm;
use ddruganov\Yii2ApiAuth\http\controllers\SecureApiController;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;
use yii\helpers\ArrayHelper;

final class RoleController extends SecureApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'rbac' => [
                'rules' => [
                    'all' => 'role.view',
                    'one' => 'role.view',
                    'create' => 'role.create',
                    'update' => 'role.update',
                    'delete' => 'role.delete',
                ]
            ]
        ]);
    }

    public function actions()
    {
        return [
            'all' => [
                'class' => FormAction::class,
                'formClass' => RoleAllCollector::class
            ],
            'one' => [
                'class' => FormAction::class,
                'formClass' => RoleOneCollector::class
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
