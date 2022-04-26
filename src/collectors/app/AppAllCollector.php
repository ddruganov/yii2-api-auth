<?php

namespace ddruganov\Yii2ApiAuth\collectors\app;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class AppAllCollector extends Form
{
    protected function _run(): ExecutionResult
    {
        return ExecutionResult::success(
            App::find()
                ->select([
                    'uuid',
                    'name',
                    'baseUrl' => 'base_url',
                    'isDefault' => 'is_default'
                ])
                ->newestFirst()
                ->asArray()
                ->all()
        );
    }
}
