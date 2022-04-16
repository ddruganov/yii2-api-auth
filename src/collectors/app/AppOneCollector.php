<?php

namespace ddruganov\Yii2ApiAuth\collectors\app;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class AppOneCollector extends Form
{
    public ?string $uuid = null;

    public function rules()
    {
        return [
            [['uuid'], 'required'],
            [['uuid'], 'exist', 'targetClass' => App::class, 'message' => 'Такого приложения не существует']
        ];
    }

    protected function _run(): ExecutionResult
    {
        $app = App::findOne($this->uuid);

        return ExecutionResult::success([
            'uuid' => $app->getUuid(),
            'name' => $app->getName(),
            'alias' => $app->getAlias(),
            'audience' => $app->getAudience(),
            'baseUrl' => $app->getBaseUrl(),
            'isDefault' => $app->isDefault() ?? false,
            'createdAt' => $app->getCreatedAt()
        ]);
    }
}
