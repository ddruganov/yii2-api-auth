<?php

namespace tests\unit\tests\rbac\role;

use ddruganov\Yii2ApiAuth\forms\rbac\role\UpdateForm;
use tests\unit\BaseUnitTest;

final class UpdateTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new UpdateForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['id', 'name', 'description'],
            noErrorKeys: ['permissionIds']
        );
    }

    public function testInvalidId()
    {
        $form = new UpdateForm(['id' => $this->getFaker()->numberBetween(10000, 20000)]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['id', 'name', 'description'],
            noErrorKeys: ['permissionIds']
        );
    }

    public function testValidId()
    {
        $form = new UpdateForm(['id' => $this->getFaker()->role()->getId()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description'],
            noErrorKeys: ['id', 'permissionIds'],
        );
    }

    public function testValid()
    {
        $form = new UpdateForm([
            'id' => $this->getFaker()->role()->getId(),
            'name' => $this->getFaker()->word(),
            'description' => $this->getFaker()->text(),
            'permissionIds' => [$this->getFaker()->permission()->getId()]
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertIsInt($result->getData('id'));
    }

    public function testSameName()
    {
        $existingRole = $this->getFaker()->role();
        $form = new UpdateForm([
            'id' => $this->getFaker()->role()->getId(),
            'name' => $existingRole->getName(),
            'description' => $this->getFaker()->text(),
            'permissionIds' => [$this->getFaker()->permission()->getId()]
        ]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name'],
            noErrorKeys: ['id', 'description', 'permissionIds'],
        );
    }
}
