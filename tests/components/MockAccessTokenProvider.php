<?php

namespace tests\components;

use ddruganov\Yii2ApiAuth\components\AccessTokenProviderInterface;

final class MockAccessTokenProvider implements AccessTokenProviderInterface
{
    private string $accessToken;

    public function setAccessToken(string $value)
    {
        $this->accessToken = $value;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
