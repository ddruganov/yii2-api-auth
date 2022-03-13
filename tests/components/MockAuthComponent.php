<?php

namespace tests\components;

use ddruganov\Yii2ApiAuth\components\AuthComponent;
use tests\unit\UseFaker;

class MockAuthComponent extends AuthComponent
{
    use UseFaker;

    public string $accessToken;

    protected function extractAccessTokenFromHeaders(): string
    {
        return $this->accessToken;
    }
}
