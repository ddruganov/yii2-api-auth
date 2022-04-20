<?php

namespace ddruganov\Yii2ApiAuth\forms\rbac\permission;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

abstract class BaseForm extends Form
{
    public ?string $name = null;
    public ?string $description = null;
    public ?string $appUuid = null;

    public function rules()
    {
        return [
            [['name', 'description', 'appUuid'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['name'], 'match', 'pattern' => '/^[a-z]+\.?[a-z]+$/', 'message' => 'Неверный формат названия'],
            [['appUuid'], 'exist', 'targetClass' => App::class, 'targetAttribute' => ['appUuid' => 'uuid']]
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = $this->getModel();
        $model->setAttributes([
            'name' => $this->name,
            'description' => $this->description,
            'app_uuid' => $this->appUuid
        ]);
        if (!$model->save()) {
            return ExecutionResult::exception('Ошибка сохранения модели');
        }

        return ExecutionResult::success([
            'id' => $model->getId()
        ]);
    }

    protected function getModel()
    {
        return new Permission();
    }
}
