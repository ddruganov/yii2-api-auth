<?php

namespace tests\unit\tests\rbac\permission;

use ddruganov\Yii2ApiAuth\collectors\rbac\permission\PermissionAllCollector;
use ddruganov\Yii2ApiAuth\collectors\rbac\permission\PermissionOneCollector;
use tests\unit\BaseUnitTest;

final class CollectorTest extends BaseUnitTest
{
    public function testAllCollector()
    {
        $this->getFaker()->permission();

        $collector = new PermissionAllCollector();
        $result = $collector->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }

    public function testOneCollector()
    {
        $collector = new PermissionOneCollector([
            'id' => $this->getFaker()->permission()->getId()
        ]);
        $result = $collector->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }
}
