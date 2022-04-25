<?php

namespace tests\components\faker;

use ddruganov\Yii2ApiAuth\forms\app\CreateForm as AppCreateForm;
use ddruganov\Yii2ApiAuth\forms\rbac\permission\CreateForm as PermissionCreateForm;
use ddruganov\Yii2ApiAuth\forms\rbac\role\CreateForm as RoleCreateForm;
use ddruganov\Yii2ApiAuth\forms\user\CreateForm as UserCreateForm;
use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiAuth\models\User;
use Exception;
use Faker\Generator as FakerGenerator;
use yii\helpers\VarDumper;

final class Generator extends FakerGenerator
{
    public function user(?Role $role = null, ?string $password = null)
    {
        $role ??= $this->role();
        $password ??= $this->password();

        $form = new UserCreateForm([
            'email' => $this->email(),
            'name' => $this->name(),
            'roleIds' => [$role->getId()],
            'password' => $password
        ]);
        $result = $form->run();
        if (!$result->isSuccessful()) {
            throw new Exception(VarDumper::dumpAsString($form->getErrors()));
        }
        return User::findOne($result->getData('id'));
    }

    public function app()
    {
        $form = new AppCreateForm([
            'name' => $this->name(),
            'alias' => $this->appAlias(),
            'audience' => $this->url(),
            'baseUrl' => $this->url(),
        ]);
        $result = $form->run();
        if (!$result->isSuccessful()) {
            throw new Exception(VarDumper::dumpAsString($form->getErrors()));
        }
        return App::findOne($result->getData('uuid'));
    }

    public function appAlias()
    {
        return $this->regexify('/^[a-z]+\.?[a-z]+$/');
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
        return $this->user(role: $role, password: $password);
    }
}
