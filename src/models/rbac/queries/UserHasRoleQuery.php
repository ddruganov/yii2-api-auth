<?php

namespace ddruganov\Yii2ApiAuth\models\rbac\queries;

use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;

final class UserHasRoleQuery extends ActiveQuery
{
    public function __construct(array $config = [])
    {
        parent::__construct(UserHasRole::class, $config);
    }

    /**
     * @return UserHasRole[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): UserHasRole|array|null
    {
        return parent::one($db);
    }

    public function byUserId(int|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'user_id' => $value
            ]);
    }

    public function byRoleId(int|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'role_id' => $value
            ]);
    }
}
