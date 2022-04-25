<?php

namespace tests\unit\tests\user;

use ddruganov\Yii2ApiAuth\forms\user\UpdateForm;
use tests\unit\BaseUnitTest;

final class UpdateTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new UpdateForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['id', 'email', 'name'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testInvalidId()
    {
        $form = new UpdateForm(['id' => $this->getFaker()->numberBetween(10000, 20000)]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['id', 'email', 'name'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testValidId()
    {
        $form = new UpdateForm(['id' => $this->getFaker()->user()->getId()]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'name'],
            noErrorKeys: ['id', 'roleIds']
        );
    }

    public function testSameEmail()
    {
        $existingUser = $this->getFaker()->user();
        $form = new UpdateForm([
            'id' => $this->getFaker()->user()->getId(),
            'email' => $existingUser->getEmail(),
            'name' => $this->getFaker()->name(),
            'roleIds' => [$this->getFaker()->role()->getId()]
        ]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email'],
            noErrorKeys: ['id', 'name', 'roleIds'],
        );
    }

    public function testValid()
    {
        $form = new UpdateForm([
            'id' => $this->getFaker()->user()->getId(),
            'email' => $this->getFaker()->email(),
            'name' => $this->getFaker()->name(),
            'roleIds' => [$this->getFaker()->role()->getId()]
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertIsInt($result->getData('id'));
    }
}
