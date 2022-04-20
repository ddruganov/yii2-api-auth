<?php

namespace tests\unit\tests\rbac\role;

use ddruganov\Yii2ApiAuth\forms\rbac\role\BaseForm;
use tests\unit\BaseUnitTest;

final class BaseFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = $this->getForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description'],
            noErrorKeys: ['permissionIds']
        );
    }

    public function testInvalidName()
    {
        $form = $this->getForm();
        $form->setAttributes(['name' => $this->getFaker()->name()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description'],
            noErrorKeys: ['permissionIds']
        );
    }

    public function testValidName()
    {
        $form = $this->getForm();
        $form->setAttributes(['name' => $this->getFaker()->word()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['description'],
            noErrorKeys: ['name', 'permissionIds']
        );
    }

    public function testDescription()
    {
        $form = $this->getForm();
        $form->setAttributes(['description' => $this->getFaker()->text()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name'],
            noErrorKeys: ['description', 'permissionIds']
        );
    }

    public function testInvalidPermissionIds()
    {
        $form = $this->getForm();
        $form->setAttributes(['permissionIds' => [$this->getFaker()->numberBetween(100, 200)]]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description', 'permissionIds']
        );
    }

    public function testValidPermissionIds()
    {
        $permission = $this->getFaker()->permission();
        $form = $this->getForm();
        $form->setAttributes(['permissionIds' => [$permission->getId()]]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description'],
            noErrorKeys: ['permissionIds']
        );
    }

    public function testValid()
    {
        $form = $this->getForm();
        $form->setAttributes([
            'name' => $this->getFaker()->word(),
            'description' => $this->getFaker()->text(),
            'permissionIds' => [$this->getFaker()->permission()->getId()]
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
    }

    private function getForm()
    {
        return new class extends BaseForm
        {
        };
    }
}
