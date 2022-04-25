<?php

namespace ddruganov\Yii2ApiAuth\collectors\user;

use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

class UserOneCollector extends Form
{
    public ?int $id = null;

    public function rules()
    {
        return [
            [['id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => User::class, 'message' => 'Пользователь не найден'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $user = User::findOne($this->id);

        return ExecutionResult::success([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'roleIds' => UserHasRole::find()
                ->select(['role_id'])
                ->byUserId($user->getId())
                ->asArray()
                ->column()
        ]);
    }
}
