<?php

namespace tests\unit\tests\rbac\permission;

use ddruganov\Yii2ApiAuth\forms\rbac\permission\BaseForm;
use tests\unit\BaseUnitTest;

final class BaseFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = $this->getForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description', 'appUuid']
        );
    }

    public function testInvalidName()
    {
        $form = $this->getForm();
        $form->setAttributes(['name' => $this->getFaker()->name()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description', 'appUuid']
        );
    }

    public function testValidName()
    {
        $form = $this->getForm();
        $form->setAttributes(['name' => $this->getFaker()->word()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['description', 'appUuid'],
            noErrorKeys: ['name']
        );
    }

    public function testDescription()
    {
        $form = $this->getForm();
        $form->setAttributes(['description' => $this->getFaker()->text()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'appUuid'],
            noErrorKeys: ['description']
        );
    }

    public function testInvalidAppUuid()
    {
        $form = $this->getForm();
        $form->setAttributes(['appUuid' => $this->getFaker()->uuid()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description', 'appUuid']
        );
    }

    public function testValidAppUuid()
    {
        $form = $this->getForm();
        $form->setAttributes(['appUuid' => $this->getFaker()->app()->getUuid()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'description'],
            noErrorKeys: ['appUuid']
        );
    }

    public function testValid()
    {
        $form = $this->getForm();
        $form->setAttributes([
            'name' => $this->getFaker()->word(),
            'description' => $this->getFaker()->text(),
            'appUuid' => $this->getFaker()->app()->getUuid()
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
