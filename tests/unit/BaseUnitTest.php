<?php

namespace tests\unit;

use Codeception\Test\Unit;
use tests\unit\UseFaker;
use Yii;
use yii\db\Transaction;
use yii\helpers\Console;
use yii\helpers\VarDumper;

abstract class BaseUnitTest extends Unit
{
    use UseFaker;

    private Transaction $transaction;

    protected function _setUp()
    {
        $this->transaction = Yii::$app->getDb()->beginTransaction();
        parent::_setUp();
    }

    protected function _tearDown()
    {
        parent::_tearDown();
        $this->transaction->rollBack();
    }

    protected function log(mixed $data)
    {
        Console::output(VarDumper::dumpAsString($data));
    }
}
