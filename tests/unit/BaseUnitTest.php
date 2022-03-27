<?php

namespace tests\unit;

use ddruganov\Yii2ApiEssentials\testing\UnitTest;
use tests\components\faker\FakerFactory;
use tests\components\faker\Generator;

abstract class BaseUnitTest extends UnitTest
{
    public string $fakerFactoryClass = FakerFactory::class;

    protected function getFaker(): Generator
    {
        return parent::getFaker();
    }
}
