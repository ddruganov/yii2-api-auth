<?php

namespace tests\components;

use ddruganov\Yii2ApiAuth\components\AuthComponent;
use ddruganov\Yii2ApiAuth\models\User;

final class MockAuthComponent extends AuthComponent
{
    private ?User $user = null;

    public function setCurrentUser(User $value)
    {
        $this->user = $value;
    }

    public function getCurrentUser(): ?User
    {
        return $this->user ?? parent::getCurrentUser();
    }
}
