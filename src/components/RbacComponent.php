<?php

namespace ddruganov\Yii2ApiAuth\components;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use yii\db\Query;

class RbacComponent implements RbacComponentInterface
{
    public function checkPermission(Permission $permission, User $user): bool
    {
        return (new Query())
            ->from(['uhr' => UserHasRole::tableName()])
            ->innerJoin(['rhp' => RoleHasPermission::tableName()], 'rhp.role_id = uhr.role_id')
            ->where([
                'rhp.permission_id' => $permission->getId(),
                'uhr.user_id' => $user->getId()
            ])
            ->exists();
    }

    public function canAuthenticate(User $user, App $app): bool
    {
        $permission = Permission::findOne([
            'app_uuid' => $app->getUuid(),
            'name' => 'authenticate'
        ]);
        if (!$permission) {
            return false;
        }

        return $this->checkPermission($permission, $user);
    }
}
