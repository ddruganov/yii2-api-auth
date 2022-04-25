<?php

namespace ddruganov\Yii2ApiAuth\forms\user;

use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

final class DeleteForm extends Form
{
    public ?int $id = null;

    public function rules()
    {
        return [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => User::class, 'message' => 'Такого пользователя не существует'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = User::findOne($this->id);
        if ($model->delete() === false) {
            return ExecutionResult::exception('Ошибка удаления пользователя');
        }

        $result = $this->deleteRoleBindings($model);
        if (!$result->isSuccessful()) {
            return $result;
        }

        return ExecutionResult::success();
    }

    private function deleteRoleBindings(User $user): ExecutionResult
    {
        $bindings = UserHasRole::find()
            ->byUserId($user->getId())
            ->all();

        foreach ($bindings as $binding) {
            if ($binding->delete() === false) {
                return ExecutionResult::exception('Ошибка удаления привязки к ролям');
            }
        }

        return ExecutionResult::success();
    }
}
