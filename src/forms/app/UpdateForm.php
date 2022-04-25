<?php

namespace ddruganov\Yii2ApiAuth\forms\app;

use ddruganov\Yii2ApiAuth\models\App;

final class UpdateForm extends BaseForm
{
    public ?string $uuid = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['uuid'], 'exist', 'skipOnEmpty' => false, 'targetClass' => App::class, 'message' => 'Такого приложения не существует'],
            [['alias'], 'filter', 'filter' => function (string $alias) {

                $existingApp = App::find()->byAlias($alias)->one();
                if ($existingApp && $existingApp->getUuid() !== $this->uuid) {
                    $this->addError('alias', 'Приложени с таким псевдонимом уже существует');
                }

                return $alias;
            }]
        ]);
    }

    protected function getModel()
    {
        return App::findOne($this->uuid);
    }
}
