<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\collectors\app\AppListCollector;
use ddruganov\Yii2ApiAuth\collectors\app\AppOneCollector;
use ddruganov\Yii2ApiEssentials\http\actions\FormAction;
use ddruganov\Yii2ApiEssentials\http\controllers\ApiController;

final class AppController extends ApiController
{
    public function actions()
    {
        return [
            'list' => [
                'class' => FormAction::class,
                'formClass' => AppListCollector::class
            ],
            'one' => [
                'class' => FormAction::class,
                'formClass' => AppOneCollector::class
            ],
        ];
    }
}
