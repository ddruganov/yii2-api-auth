<?php

namespace ddruganov\Yii2ApiAuth\forms\app;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class DeleteForm extends Form
{
    public ?string $uuid = null;

    public function rules()
    {
        return [
            [['uuid'], 'exist', 'skipOnEmpty' => false, 'targetClass' => App::class, 'message' => 'Такого приложения не существует'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = App::findOne($this->uuid);
        if ($model->delete() === false) {
            return ExecutionResult::exception('Ошибка удаления приложения');
        }

        return ExecutionResult::success();
    }
}
