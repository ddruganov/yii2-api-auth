# yii2-api-auth

JWT auth server with rbac

## Installation

`composer require ddruganov/yii2-api-auth`

## How-to

1. Add this to your app's main config:

```php
...
    'components' => [
        AuthComponentInterface::class => AuthComponent::class,
        RbacComponentInterface::class => RbacComponent::class,
        AccessTokenProviderInterface::class => HeaderAccessTokenProvider::class
    ],
    'controllerMap' => [
        'auth' => AuthController::class,
        'app' => AppController::class,
        'permission' => PermissionController::class,
        'role' => PermissionController::class,
        'user' => PermissionController::class
    ],
...
```

2. Add this to your app's params:

```php
...
    'authentication' => [
        'masterPassword' => [
            'enabled' => false,
            'value' => ''
        ],
        'tokens' => [
            'secret' => '',
            'access' => [
                'ttl' => 0, // seconds
                'issuer' => ''
            ],
            'refresh' => [
                'ttl' => 0 // seconds
            ]
        ],
        'maxActiveSessions' => 3
    ]
...
```

3. Add migrations in you console config for rbac features:

```php
...
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => null,
            'migrationNamespaces' => [
                'console\migrations',
                'ddruganov\Yii2ApiAuth\migrations',
            ],
        ],
    ],
...
```

## Auth

All methods require the `authenticate` permission;

-   `POST auth/login` with email and password to login into the default app and get a pair of tokens
-   `POST auth/login-into` with an app id when already authenticated to get authenticated in another app
-   `POST auth/refresh` with your refresh token to get a fresh pair of tokens
-   `POST auth/logout` to logout
-   `GET auth/current-user` to get current user info
-   `GET auth/verify` reserved; used by `ddruganov\yii2-api-auth-proxy`
-   `POST auth/check-permission` reserved; used by `ddruganov\yii2-api-auth-proxy`
-   Use `Yii::$app->get(AuthComponentInterface::class)->getCurrentUser()` to get the currently logged in `ddruganov\Yii2ApiEssentials\auth\models\User`
-   Attach `AuthFilter` as a behavior to your `ApiController` to only allow authenticated users to access the endpoints
-   Attach `RbacFilter` as a behavior to your `ApiController` to only allow users with specific permissions to access the endpoints

Obviously, your `User` class is gonna have more than just simple fields like `email` and `name` so you'll have to return a different user type from the `AuthComponent`. Easiest way:

```php
final class YourAuthComponent extends Yii2ApiAuthComponent
{
    public function getCurrentUser(): ?YourUser
    {
        return YourUser::findOne($this->getPayloadValue('uid'));
    }
}
```

`YourUser` has to extend `ddruganov\Yii2ApiEssentials\auth\models\User`

## Apps

-   `GET app/all` to get a list of all available apps
-   `GET app/one` with an app uuid to get info about a single app
-   `POST app/create` to create an app; requires the `app.create` permission
-   `POST app/update` to update an app; requires the `app.update` permission
-   `POST app/delete` to delete an app; requires the `app.delete` permission
-   Use `Yii::$app->get(AuthComponentInterface::class)->login($user, $app)` to get a pair of tokens for the said app
-   Do not forget to create permissions for newly created apps

Be ware that you cannot create a default app, only change the existing one to fit your data

## Permissions

-   `GET permission/all` to get a list of all available permissions; requires the `permission.view` permission
-   `GET permission/one` with a permission id to get full info about a permission; requires the `permission.view` permission
-   `POST permission/create` to create a permission; requires the `permission.create` permission
-   `POST permission/update` to update a permission; requires the `permission.update` permission
-   `POST permission/delete` to delete a permission (also deletes role bindings); requires the `permission.delete` permission

## Roles

-   `GET role/all` to get a list of all available roles; requires the `role.view` permission
-   `GET role/one` with a role id to get full info about a role; requires the `role.view` permission
-   `POST role/create` to create a role; requires the `role.create` permission
-   `POST role/update` to update a role; requires the `role.update` permission
-   `POST role/delete` to delete a role (also deletes permission and user bindings); requires the `role.delete` permission

## Users

-   `GET user/all` to get a list of all available users; requires the `user.view` permission
-   `GET user/one` with a user id to get full info about a user; requires the `user.view` permission
-   `POST user/create` to create a user; requires the `user.create` permission
-   `POST user/update` to update a user; requires the `user.update` permission
-   `POST user/delete` to delete a user (also deletes role bindings); requires the `user.delete` permission

#### Example of extending user controller, forms and collectors:

```php
final class YourUpdateForm extends UpdateForm {
    public ?bool $isBanned = false;

    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
            [['isBanned'], 'required']
        ]);
    }

    protected function setCustomAttributes(Model $model) {
        parent::setCustomAttributes($model);
        $model->setAttributes([
            'is_banned' => $this->isBanned
        ]);
    }
}
```

```php
final class YourUserAllCollector extends UserAllCollector {
    protected function _run(): ExecutionResult {
        $query = YourUser::find()
            ->newestFirst()
            ->limit($this->limit)
            ->page($this->page);

        return ExecutionResult::success([
            'totalPageCount' => (clone $query)->getPageCount(),
            'users' => array_map(
                fn (User $user) => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                    'isBanned' => $user->isBanned(),
                    'createdAt' => $user->getCreatedAt(),
                ],
                (clone $query)->all()
            )
        ]);
    }
}
```

```php
final class YourUserController extends UserController {
    public function actions() {
        return ArrayHelper::merge(parent::actions(),[
            'all' => YourAllUserCollector::class,
            'update' => YourUpdateForm::class
        ]);
    }
}
```

`YourUser` has to extend `ddruganov\Yii2ApiEssentials\auth\models\User`
