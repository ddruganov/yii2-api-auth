<?php

namespace tests\components\faker;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiAuth\models\rbac\RoleHasPermission;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use Exception;
use Faker\Generator as FakerGenerator;
use Yii;
use yii\helpers\VarDumper;

class Generator extends FakerGenerator
{
    public function user(?Role $role = null)
    {
        $model = new User([
            'email' => $this->email(),
            'name' => $this->name(),
            'password' => Yii::$app->getSecurity()->generatePasswordHash($this->password())
        ]);
        if (!$model->save()) {
            throw new Exception(VarDumper::dumpAsString($model->getFirstErrors()));
        }

        if ($role) {
            $userHasRole = new UserHasRole([
                'user_id' => $model->getId(),
                'role_id' => $role->getId()
            ]);
            $userHasRole->save();
        }

        return $model;
    }

    public function app()
    {
        $model = new App([
            'name' => $this->name(),
            'alias' => $this->asciify(),
            'audience' => $this->asciify(),
            'url' => $this->url(),
            'is_default' => false
        ]);
        if (!$model->save()) {
            throw new Exception(VarDumper::dumpAsString($model->getFirstErrors()));
        }
        return $model;
    }

    public function permission(string $name, App $app)
    {
        $model = new Permission([
            'name' => $name,
            'app_uuid' => $app->getUuid(),
            'description' => $this->text()
        ]);
        if (!$model->save()) {
            throw new Exception(VarDumper::dumpAsString($model->getFirstErrors()));
        }
        return $model;
    }

    public function role(string $name, array $permissions = [])
    {
        $model = new Role([
            'name' => $name,
            'description' => $this->text()
        ]);
        if (!$model->save()) {
            throw new Exception(VarDumper::dumpAsString($model->getFirstErrors()));
        }

        foreach ($permissions as $permission) {
            $link = new RoleHasPermission([
                'role_id' => $model->getId(),
                'permission_id' => $permission->getId()
            ]);
            $link->save();
        }

        return $model;
    }
}
