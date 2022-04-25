<?php

namespace tests\unit\tests\app;

use ddruganov\Yii2ApiAuth\forms\app\BaseForm;
use tests\unit\BaseUnitTest;

final class BaseFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = $this->getForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'alias', 'audience', 'baseUrl'],
        );
    }

    public function testValidName()
    {
        $form = $this->getForm();
        $form->setAttributes(['name' => $this->getFaker()->text(20)]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['alias', 'audience', 'baseUrl'],
            noErrorKeys: ['name']
        );
    }

    public function testInvalidAlias()
    {
        $form = $this->getForm();
        $form->setAttributes(['alias' => $this->getFaker()->text(20)]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['alias', 'name', 'audience', 'baseUrl'],
        );
    }

    public function testValidAlias()
    {
        $form = $this->getForm();
        $form->setAttributes(['alias' => $this->getFaker()->appAlias()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'audience', 'baseUrl'],
            noErrorKeys: ['alias']
        );
    }

    public function testValidAudience()
    {
        $form = $this->getForm();
        $form->setAttributes(['audience' => $this->getFaker()->url()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'alias', 'baseUrl'],
            noErrorKeys: ['audience']
        );
    }

    public function testValidBaseUrl()
    {
        $form = $this->getForm();
        $form->setAttributes(['baseUrl' => $this->getFaker()->url()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'alias', 'audience'],
            noErrorKeys: ['baseUrl']
        );
    }

    public function testValid()
    {
        $form = $this->getForm();
        $form->setAttributes([
            'name' => $this->getFaker()->text(20),
            'alias' => $this->getFaker()->appAlias(),
            'audience' => $this->getFaker()->url(),
            'baseUrl' => $this->getFaker()->url(),
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertIsString($result->getData('uuid'));
    }

    private function getForm()
    {
        return new class extends BaseForm
        {
        };
    }
}
