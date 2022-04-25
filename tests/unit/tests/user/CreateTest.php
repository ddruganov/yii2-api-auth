<?php

namespace tests\unit\tests\user;

use ddruganov\Yii2ApiAuth\forms\user\CreateForm;
use tests\unit\BaseUnitTest;

final class CreateTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new CreateForm();
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'name', 'password'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testSameEmail()
    {
        $existingUser = $this->getFaker()->user();

        $form = new CreateForm([
            'email' => $existingUser->getEmail(),
        ]);
        $result = $form->run();

        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'name', 'password'],
            noErrorKeys: ['roleIds']
        );
    }

    public function testValid()
    {
        $form = new CreateForm([
            'email' => $this->getFaker()->email(),
            'name' => $this->getFaker()->name(),
            'roleIds' => [$this->getFaker()->role()->getId()],
            'password' => $this->getFaker()->password()
        ]);
        $result = $form->run();

        $this->assertExecutionResultSuccessful($result);
        $this->assertIsInt($result->getData('id'));
    }
}
