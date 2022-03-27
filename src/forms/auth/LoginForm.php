<?php

namespace ddruganov\Yii2ApiAuth\forms\auth;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;
use Yii;

class LoginForm extends Form
{
    public ?string $email = null;
    public ?string $password = null;
    public ?string $appUuid = null;

    public function rules()
    {
        return [
            [['email', 'password', 'appUuid'], 'required'],
            [['email', 'password', 'appUuid'], 'string'],
            [['email'], 'email'],
            [['email'], 'exist', 'targetClass' => User::class, 'message' => 'Такого пользователя не существует'],
            [['password'], 'filter', 'filter' => function (string $password) {
                if ($user = User::findOne(['email' => $this->email])) {
                    $isMasterPassword = $this->isMasterPassword();
                    $isRightPassword = Yii::$app->getSecurity()->validatePassword($this->password, $user->getPassword());
                    if (!$isMasterPassword && !$isRightPassword) {
                        $this->addError('password', 'Неверный пароль');
                    }
                }

                return $password;
            }],
            [['appUuid'], 'exist', 'targetClass' => App::class, 'targetAttribute' => ['appUuid' => 'uuid'], 'message' => 'Такого приложения не существует'],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $user = User::findOne(['email' => $this->email]);
        $app = App::findOne($this->appUuid);

        return Yii::$app->get(AuthComponentInterface::class)->login($user, $app);
    }

    private function isMasterPassword()
    {
        $masterPassword = Yii::$app->params['authentication']['masterPassword'] ?? ['enabled' => false];
        if (!$masterPassword['enabled']) {
            return false;
        }

        return $this->password === $masterPassword['value'];
    }
}
