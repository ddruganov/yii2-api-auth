<?php

namespace ddruganov\Yii2ApiAuth\components;

use Yii;

final class HeaderAccessTokenProvider implements AccessTokenProviderInterface
{
    public function getAccessToken(): string
    {
        $accessToken = Yii::$app->getRequest()->getHeaders()->get('Authorization') ?? '';
        $accessToken = str_replace('Bearer', '', $accessToken);
        return trim($accessToken);
    }
}
