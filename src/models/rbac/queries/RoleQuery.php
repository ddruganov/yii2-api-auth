<?php

namespace ddruganov\Yii2ApiAuth\models\rbac\queries;

use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiEssentials\traits\Pagination;
use ddruganov\Yii2ApiEssentials\traits\Sorting;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;

final class RoleQuery extends ActiveQuery
{
    use Pagination, Sorting;

    public function __construct(array $config = [])
    {
        parent::__construct(Role::class, $config);
    }

    /**
     * @return Role[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): Role|array|null
    {
        return parent::one($db);
    }

    public function byId(int|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'id' => $value
            ]);
    }

    public function byName(string|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'name' => $value
            ]);
    }
}
