<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\collectors\app\AppAllCollector;
use ddruganov\Yii2ApiAuth\collectors\app\AppOneCollector;
use ddruganov\Yii2ApiAuth\forms\app\CreateForm;
use ddruganov\Yii2ApiAuth\forms\app\DeleteForm;
use ddruganov\Yii2ApiAuth\forms\app\UpdateForm;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;
use ddruganov\Yii2ApiEssentials\http\controllers\ApiController;
use yii\helpers\ArrayHelper;

final class AppController extends ApiController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'auth' => [
                'exceptions' => ['all', 'one']
            ],
            'rbac' => [
                'rules' => [
                    'create' => 'app.create',
                    'update' => 'app.update',
                    'delete' => 'app.delete'
                ],
                'exceptions' => ['all', 'one']
            ]
        ]);
    }

    public function actions()
    {
        return [
            'all' => [
                'class' => FormAction::class,
                'formClass' => AppAllCollector::class
            ],
            'one' => [
                'class' => FormAction::class,
                'formClass' => AppOneCollector::class
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
