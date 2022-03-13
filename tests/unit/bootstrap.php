<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@root', __DIR__ . '/../../');
Yii::setAlias('@tests', Yii::getAlias('@root/tests'));

$config = require Yii::getAlias('@tests/config/main.php');

$application = new yii\console\Application($config);
