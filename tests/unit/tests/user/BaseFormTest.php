<?php

namespace tests\unit\tests\user;

use ddruganov\Yii2ApiAuth\forms\user\BaseForm;
use tests\unit\BaseUnitTest;
use yii\base\Model;

final class BaseFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = $this->getForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'name'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testInvalidEmail()
    {
        $form = $this->getForm();
        $form->setAttributes(['email' => $this->getFaker()->word()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'name'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testValidEmail()
    {
        $form = $this->getForm();
        $form->setAttributes(['email' => $this->getFaker()->email()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name'],
            noErrorKeys: ['email', 'roleIds']
        );
    }

    public function testInvalidName()
    {
        $form = $this->getForm();
        $form->setAttributes(['name' => '']);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['name', 'email'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testValidName()
    {
        $form = $this->getForm();
        $form->setAttributes(['name' => $this->getFaker()->name()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email'],
            noErrorKeys: ['name', 'roleIds']
        );
    }

    public function testInvalidRoleIds()
    {
        $form = $this->getForm();
        $form->setAttributes(['roleIds' => [$this->getFaker()->numberBetween(100, 200)]]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'name', 'roleIds'],
        );
    }

    public function testValidRoleIds()
    {
        $form = $this->getForm();
        $form->setAttributes(['roleIds' => [$this->getFaker()->role()->getId()]]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'name'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testValid()
    {
        $form = $this->getForm();
        $form->setAttributes([
            'email' => $this->getFaker()->email(),
            'name' => $this->getFaker()->name(),
            'roleIds' => [$this->getFaker()->role()->getId()]
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertIsInt($result->getData('id'));
    }

    private function getForm()
    {
        return new class extends BaseForm
        {
            protected function setCustomAttributes(Model $model)
            {
                $model->setAttributes([
                    'password' => 'test' // only appears in the creation form but is needed to create a user entry in the db
                ]);
            }
        };
    }
}
