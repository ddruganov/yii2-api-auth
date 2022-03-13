<?php

namespace tests\unit;

use tests\components\faker\FakerFactory;
use tests\components\faker\Generator;

trait UseFaker
{
    private Generator $generator;

    protected function faker(): Generator
    {
        return $this->generator ??= FakerFactory::create();
    }
}
