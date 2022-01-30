# yii2-api-auth

## Auth module

1. Add this to your app's params config:

```php
...
    'authentication' => [
        'loginForm' => LoginForm::class, // default is ddruganov\Yii2ApiAuth\models\forms\LoginForm
        'masterPassword' => [
            'enable' => true,
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

2. Install the `AuthController` in your api app main config
3. Install the `AuthComponent` in you api app main config to reach it as `Yii::$app->get('auth')`
4. Login in via `auth/login`
5. Get a fresh pair of token via `auth/refresh` by POSTing a currently valid refresh token
6. Logout via `auth/logout`

Use `Yii::$app->get('auth')->getCurrentUser()` to get the currently logged in `ddruganov\Yii2ApiEssentials\auth\models\User`
