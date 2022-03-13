<?php

namespace ddruganov\Yii2ApiAuth\components;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\ExecutionResult;

interface AuthComponentInterface
{
    public function login(User $user, App $app): ExecutionResult;
    public function verify(): bool;
    public function logout(): ExecutionResult;
    public function refresh(string $refreshToken): ExecutionResult;

    public function getPayloadValue(string $key, mixed $default = null): mixed;
    public function getCurrentUser(): ?User;
    public function getCurrentApp(): ?App;
}
