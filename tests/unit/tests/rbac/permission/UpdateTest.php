<?php

namespace tests\unit\tests\rbac\permission;

use ddruganov\Yii2ApiAuth\forms\rbac\permission\UpdateForm;
use tests\unit\BaseUnitTest;

final class UpdateTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new UpdateForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['id', 'name', 'description', 'appUuid']
        );
    }

    public function testInvalidId()
    {
        $form = new UpdateForm(['id' => $this->getFaker()->numberBetween(10000, 20000)]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['id', 'name', 'description', 'appUuid']
        );
    }

    public function testValidId()
    {
        $form = new UpdateForm(['id' => $this->getFaker()->permission()->getId()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description', 'appUuid'],
            noErrorKeys: ['id'],
        );
    }

    public function testValid()
    {
        $form = new UpdateForm([
            'id' => $this->getFaker()->permission()->getId(),
            'name' => $this->getFaker()->word(),
            'description' => $this->getFaker()->text(),
            'appUuid' => $this->getFaker()->app()->getUuid()
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertIsInt($result->getData('id'));
    }

    public function testSameName()
    {
        $existingPermission = $this->getFaker()->permission();
        $form = new UpdateForm([
            'id' => $this->getFaker()->permission()->getId(),
            'name' => $existingPermission->getName(),
            'description' => $this->getFaker()->text(),
            'appUuid' => $existingPermission->getAppUuid()
        ]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'appUuid'],
            noErrorKeys: ['id', 'description'],
        );
    }
}
