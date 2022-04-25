<?php

namespace ddruganov\Yii2ApiAuth\collectors\user;

use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;

class UserAllCollector extends Form
{
    public ?int $page = 1;
    public ?int $limit = 10;

    public function rules()
    {
        return [
            [['page', 'limit'], 'required']
        ];
    }

    protected function _run(): ExecutionResult
    {
        $query = User::find()
            ->newestFirst()
            ->limit($this->limit)
            ->page($this->page);

        return ExecutionResult::success([
            'totalPageCount' => (clone $query)->getPageCount(),
            'users' => array_map(
                fn (User $user) => $this->processRow($user),
                (clone $query)->all()
            )
        ]);
    }

    private function processRow(User $user)
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'createdAt' => $user->getCreatedAt(),
        ];
    }
}
