<?php

namespace ddruganov\Yii2ApiAuth\http\controllers;

use ddruganov\Yii2ApiAuth\http\filters\AuthFilter;
use ddruganov\Yii2ApiEssentials\http\controllers\ApiController;

abstract class SecureApiController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'auth' => [
                'class' => AuthFilter::class
            ]
        ]);
    }
}
