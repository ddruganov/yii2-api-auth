<?php

namespace ddruganov\Yii2ApiAuth\exeptions;

use Exception;

class UnauthenticatedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Ваша авторизация недействительна');
    }
}
