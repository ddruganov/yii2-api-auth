<?php

namespace ddruganov\Yii2ApiAuth\forms\app;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

abstract class BaseForm extends Form
{
    public ?string $name = null;
    public ?string $alias = null;
    public ?string $audience = null;
    public ?string $baseUrl = null;

    public function rules()
    {
        return [
            [['name', 'alias', 'audience', 'baseUrl'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['alias'], 'match', 'pattern' => '/^[a-z]+\.?[a-z]+$/', 'message' => 'Неверный формат псевдонима']
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = $this->getModel();
        $model->setAttributes([
            'name' => $this->name,
            'alias' => $this->alias,
            'audience' => $this->audience,
            'base_url' => $this->baseUrl
        ]);
        if (!$model->save()) {
            return ExecutionResult::exception('Ошибка сохранения модели');
        }

        return ExecutionResult::success([
            'uuid' => $model->getUuid()
        ]);
    }

    protected function getModel()
    {
        return new App();
    }
}
