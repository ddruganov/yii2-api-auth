<?php

namespace ddruganov\Yii2ApiAuth\components;

interface AccessTokenProviderInterface
{
    public function getAccessToken(): ?string;
}
