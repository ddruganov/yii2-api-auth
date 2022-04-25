<?php

namespace tests\unit\tests\user;

use ddruganov\Yii2ApiAuth\collectors\user\UserAllCollector;
use ddruganov\Yii2ApiAuth\collectors\user\UserOneCollector;
use tests\unit\BaseUnitTest;

final class CollectorTest extends BaseUnitTest
{
    public function testAllCollector()
    {
        $this->getFaker()->user();

        $collector = new UserAllCollector();
        $result = $collector->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }

    public function testOneCollector()
    {
        $collector = new UserOneCollector([
            'id' => $this->getFaker()->user()->getId()
        ]);
        $result = $collector->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }
}
