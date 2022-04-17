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
        'app' => AppController::class
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

-   `POST auth/login` with email and password to login into the default app and get a pair of tokens
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

-   `GET app/list` to get a list of all available apps
-   `GET app/one` with an app uuid to get info about a single app
-   Create apps with `\ddruganov\Yii2ApiAuth\models\App`
-   Use `Yii::$app->get(AuthComponentInterface::class)->login($user, $app)` to get a pair of tokens for the said app
-   Do not forget to create permissions for newly created apps
