<?php

namespace tests\unit\tests;

use ddruganov\Yii2ApiAuth\collectors\app\AppListCollector;
use ddruganov\Yii2ApiAuth\collectors\app\AppOneCollector;
use ddruganov\Yii2ApiAuth\models\App;
use tests\unit\BaseUnitTest;

final class AppCollectorTest extends BaseUnitTest
{
    public function testListCollector()
    {
        $existingCount = App::find()->count();

        $newCount = 3;
        for ($i = 0; $i < $newCount; $i++) {
            $this->getFaker()->app();
        }

        $collector = new AppListCollector();
        $result = $collector->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertCount($existingCount + $newCount, $result->getData());
    }

    public function testOneCollector()
    {
        $collector = new AppOneCollector([
            'uuid' => $this->getFaker()->app()->getUuid()
        ]);
        $result = $collector->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }
}
