# yii2-api-auth

## Auth module

1. Add this to your app's params config:

```php
...
    'authentication' => [
        'loginForm' => LoginForm::class, // default is \ddruganov\Yii2ApiAuth\models\forms\LoginForm
        'masterPassword' => [
            'enabled' => true,
            'value' => ''
        ],
        'tokens' => [
            'secret' => '',
            'access' => [
                'ttl' => 0, // seconds
                'issuer' => '',
                'audience' => ''
            ],
            'refresh' => [
                'ttl' => 0 // seconds
            ]
        ]
    ]
...
```

2. Install the `AuthController` in your api controller map
3. Install the `AuthComponent` in you api app main config to reach it as `Yii::$app->get('auth')`
4. Install the `RbacComponent` in you api app main config to reach it as `Yii::$app->get('rbac')`
5. Login in via `auth/login`
6. Get a fresh pair of token via `auth/refresh` by POSTing a currently valid refresh token
7. Logout via `auth/logout`

Use `Yii::$app->get('auth')->getCurrentUser()` to get the currently logged in `ddruganov\Yii2ApiEssentials\auth\models\User`
