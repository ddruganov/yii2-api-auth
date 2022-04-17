<?php

namespace ddruganov\Yii2ApiAuth\collectors\user;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;
use Yii;

class CurrentUserCollector extends Form
{
    protected function _run(): ExecutionResult
    {
        /** @var \ddruganov\Yii2ApiAuth\models\User */
        $user = Yii::$app->get(AuthComponentInterface::class)->getCurrentUser();

        return ExecutionResult::success([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName()
        ]);
    }
}
