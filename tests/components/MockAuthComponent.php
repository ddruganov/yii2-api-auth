<?php

namespace tests\components;

use ddruganov\Yii2ApiAuth\components\AuthComponent;

final class MockAuthComponent extends AuthComponent
{
    private string $accessToken;

    protected function extractAccessTokenFromHeaders(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $value)
    {
        $this->accessToken = $value;
    }
}
