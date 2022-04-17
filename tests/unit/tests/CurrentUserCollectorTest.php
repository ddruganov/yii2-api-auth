<?php

namespace tests\unit\tests;

use ddruganov\Yii2ApiAuth\collectors\user\CurrentUserCollector;
use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use tests\components\MockAuthComponent;
use tests\unit\BaseUnitTest;
use Yii;

final class CurrentUserCollectorTest extends BaseUnitTest
{
    public function testCollector()
    {
        $this->getAuth()->setCurrentUser($this->getFaker()->user());

        $collector = new CurrentUserCollector();
        $result = $collector->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotEmpty($result->getData());
    }

    private function getAuth(): MockAuthComponent
    {
        return Yii::$app->get(AuthComponentInterface::class);
    }
}
