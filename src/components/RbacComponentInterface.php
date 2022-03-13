<?php

namespace ddruganov\Yii2ApiAuth\components;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\User;

interface RbacComponentInterface
{
    public function checkPermission(Permission $permission, User $user): bool;
    public function canAuthenticate(User $user, App $app): bool;
}
