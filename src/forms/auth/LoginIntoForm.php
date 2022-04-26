<?php

namespace ddruganov\Yii2ApiAuth\forms\auth;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;
use Yii;

final class LoginIntoForm extends Form
{
    public ?string $appUuid = null;

    public function rules()
    {
        return [
            [['appUuid'], 'exist', 'skipOnEmpty' => false, 'targetClass' => App::class, 'targetAttribute' => ['appUuid' => 'uuid'], 'message' => 'Такого приложения не существует']
        ];
    }

    protected function _run(): ExecutionResult
    {
        /** @var \ddruganov\Yii2ApiAuth\components\AuthComponentInterface */
        $auth = Yii::$app->get(AuthComponentInterface::class);
        $currentUser = $auth->getCurrentUser();

        $app = App::find()->byUuid($this->appUuid)->one();

        return $auth->login($currentUser, $app);
    }
}
