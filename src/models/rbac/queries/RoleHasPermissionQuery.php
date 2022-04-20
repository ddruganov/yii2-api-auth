<?php

namespace ddruganov\Yii2ApiAuth\models\rbac\queries;

use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;

final class RoleHasPermissionQuery extends ActiveQuery
{
    public function __construct(array $config = [])
    {
        parent::__construct(RoleHasPermission::class, $config);
    }

    /**
     * @return RoleHasPermission[]
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    public function one($db = null): RoleHasPermission|array|null
    {
        return parent::one($db);
    }

    public function byRoleId(int|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'role_id' => $value
            ]);
    }

    public function byPermissionId(int|array|QueryInterface $value)
    {
        return $this
            ->andWhere([
                'permission_id' => $value
            ]);
    }
}
