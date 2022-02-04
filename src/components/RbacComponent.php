<?php

namespace ddruganov\Yii2ApiAuth\components;

use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use yii\base\Component;
use yii\db\Expression;
use yii\db\Query;

class RbacComponent extends Component
{
    public function checkPermission(Permission $permission, User $user)
    {
        return (new Query())
            ->select([new Expression('1')])
            ->from(['uhr' => UserHasRole::tableName()])
            ->innerJoin(['rhp' => RoleHasPermission::tableName()], 'rhp.role_id = uhr.role_id')
            ->where([
                'rhp.permission_id' => $permission->getId(),
                'uhr.user_id' => $user->getId()
            ])
            ->exists();
    }

    public function canAuthenticate(User $user)
    {
        return $this->checkPermission(Permission::findOne(['name' => 'authenticate']), $user);
    }
}
