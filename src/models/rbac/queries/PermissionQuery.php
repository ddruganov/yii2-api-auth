<?php

namespace ddruganov\Yii2ApiAuth\models\rbac\queries;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiEssentials\traits\Pagination;
use ddruganov\Yii2ApiEssentials\traits\Sorting;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;

final class PermissionQuery extends ActiveQuery
{
    use Pagination, Sorting;

    public function __construct(array $config = [])
    {
        parent::__construct(Permission::class, $config);
    }

    /**
     * @return Permission[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): Permission|array|null
    {
        return parent::one($db);
    }

    public function byId(int|array|QueryInterface|null $value)
    {
        return $this
            ->andWhere([
                'id' => $value
            ]);
    }

    public function byName(string|array|QueryInterface|null $value)
    {
        return $this
            ->andWhere([
                'name' => $value
            ]);
    }

    public function byAppUuid(string|array|QueryInterface|null $value)
    {
        return $this
            ->andWhere([
                'app_uuid' => $value
            ]);
    }
}
