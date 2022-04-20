<?php

namespace ddruganov\Yii2ApiAuth\models\rbac;

use ddruganov\Yii2ApiAuth\models\rbac\queries\RoleHasPermissionQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $role_id
 * @property int $permission_id
 */
final class RoleHasPermission extends ActiveRecord
{
    public static function tableName()
    {
        return 'rbac.role_has_permission';
    }

    public static function find(): RoleHasPermissionQuery
    {
        return new RoleHasPermissionQuery();
    }

    public function rules()
    {
        return [
            [['role_id', 'permission_id'], 'required'],
            [['role_id', 'permission_id'], 'integer'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            [['permission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Permission::class, 'targetAttribute' => ['permission_id' => 'id']],
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

    public function getRole()
    {
        return Role::findOne($this->getRoleId());
    }

    public function getPermissionId()
    {
        return $this->permission_id;
    }

    public function getPermission()
    {
        return Permission::findOne($this->getPermissionId());
    }
}
