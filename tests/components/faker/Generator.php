<?php

namespace tests\components\faker;

use ddruganov\Yii2ApiAuth\forms\rbac\permission\CreateForm as PermissionCreateForm;
use ddruganov\Yii2ApiAuth\forms\rbac\role\CreateForm as RoleCreateForm;
use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use Exception;
use Faker\Generator as FakerGenerator;
use Yii;
use yii\helpers\VarDumper;

final class Generator extends FakerGenerator
{
    public function user(?Role $role = null, ?string $password = null)
    {
        $password ??= $this->password();
        $model = new User([
            'email' => $this->email(),
            'name' => $this->name(),
            'password' => Yii::$app->getSecurity()->generatePasswordHash($password)
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
            'base_url' => $this->url(),
            'is_default' => null
        ]);
        if (!$model->save()) {
            throw new Exception(VarDumper::dumpAsString($model->getFirstErrors()));
        }
        return $model;
    }

    public function permission(?string $name = null, ?App $app = null)
    {
        $name ??= $this->permissionName();
        $app ??= $this->app();
        $form = new PermissionCreateForm([
            'name' => $name,
            'appUuid' => $app->getUuid(),
            'description' => $this->text()
        ]);
        $result = $form->run();
        if (!$result->isSuccessful()) {
            throw new Exception(VarDumper::dumpAsString($form->getErrors()));
        }
        return Permission::findOne($result->getData('id'));
    }

    public function permissionName()
    {
        return $this->word();
    }

    public function role(?string $name = null, array $permissionIds = [])
    {
        $name ??= $this->word();
        $form = new RoleCreateForm([
            'name' => $name,
            'description' => $this->text(),
            'permissionIds' => $permissionIds
        ]);
        $result = $form->run();
        if (!$result->isSuccessful()) {
            throw new Exception(VarDumper::dumpAsString($result->getErrors()));
        }
        return Role::findOne($result->getData('id'));
    }

    public function userWithAuthenticatePermission(App $app, ?string $password = null)
    {
        $permission = $this->permission('authenticate', $app);
        $role = $this->role('test', [$permission->getId()]);
        return $this->user($role, $password);
    }
}
