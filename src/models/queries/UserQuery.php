<?php

namespace ddruganov\Yii2ApiAuth\models\queries;

use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\traits\Pagination;
use ddruganov\Yii2ApiEssentials\traits\Sorting;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;

final class UserQuery extends ActiveQuery
{
    use Pagination, Sorting;

    public function __construct(array $config = [])
    {
        parent::__construct(User::class, $config);
    }

    /**
     * @return User[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): User|array|null
    {
        return parent::one($db);
    }

    public function byEmail(string|array|QueryInterface|null $value)
    {
        return $this
            ->andWhere([
                'email' => $value
            ]);
    }
}
