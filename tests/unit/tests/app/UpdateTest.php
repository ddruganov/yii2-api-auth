<?php

namespace tests\unit\tests\app;

use ddruganov\Yii2ApiAuth\forms\app\UpdateForm;
use tests\unit\BaseUnitTest;

final class UpdateTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new UpdateForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['uuid', 'name', 'alias', 'audience', 'baseUrl']
        );
    }

    public function testInvalidUuid()
    {
        $form = new UpdateForm(['uuid' => $this->getFaker()->uuid()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['uuid', 'name', 'alias', 'audience', 'baseUrl']
        );
    }

    public function testValidUuid()
    {
        $form = new UpdateForm(['uuid' => $this->getFaker()->app()->getUuid()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'alias', 'audience', 'baseUrl'],
            noErrorKeys: ['uuid']
        );
    }

    public function testSameAlias()
    {
        $existingApp = $this->getFaker()->app();

        $form = new UpdateForm(['alias' => $existingApp->getAlias()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['uuid', 'name', 'alias', 'audience', 'baseUrl']
        );
    }

    public function testValid()
    {
        $form = new UpdateForm([
            'uuid' => $this->getFaker()->app()->getUuid(),
            'name' => $this->getFaker()->text(20),
            'alias' => $this->getFaker()->appAlias(),
            'audience' => $this->getFaker()->url(),
            'baseUrl' => $this->getFaker()->url(),
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertIsString($result->getData('uuid'));
    }
}
