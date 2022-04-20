<?php

namespace tests\unit\tests\rbac\role;

use ddruganov\Yii2ApiAuth\collectors\rbac\role\RoleAllCollector;
use ddruganov\Yii2ApiAuth\collectors\rbac\role\RoleOneCollector;
use tests\unit\BaseUnitTest;

final class CollectorTest extends BaseUnitTest
{
    public function testAllCollector()
    {
        $this->getFaker()->role();

        $collector = new RoleAllCollector();
        $result = $collector->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }

    public function testOneCollector()
    {
        $collector = new RoleOneCollector([
            'id' => $this->getFaker()->role()->getId()
        ]);
        $result = $collector->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }
}
