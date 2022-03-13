<?php

namespace tests\unit\tests;

use ddruganov\Yii2ApiAuth\models\forms\LoginForm;
use tests\unit\BaseUnitTest;

final class LoginFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new LoginForm();
        $this->assertFalse($form->validate());
        $this->assertNotEmpty($form->getErrors());
        $this->assertNotNull($form->getFirstError('email'));
        $this->assertNotNull($form->getFirstError('password'));
    }

    public function testInvalidEmail()
    {
        $form = new LoginForm([
            'email' => $this->faker()->email()
        ]);
        $this->assertFalse($form->validate());
        $this->assertNotEmpty($form->getErrors());
        $this->assertNotNull($form->getFirstError('email'));
        $this->assertNotNull($form->getFirstError('password'));
    }

    public function testValidEmail()
    {
        $user = $this->faker()->user();
        $form = new LoginForm([
            'email' => $user->getEmail()
        ]);
        $this->assertFalse($form->validate());
        $this->assertNotEmpty($form->getErrors());
        $this->assertNull($form->getFirstError('email'));
        $this->assertNotNull($form->getFirstError('password'));
    }

    public function testPasswordOnly()
    {
        $form = new LoginForm([
            'password' => $this->faker()->password()
        ]);
        $this->assertFalse($form->validate());
        $this->assertNotEmpty($form->getErrors());
        $this->assertNotNull($form->getFirstError('email'));
        $this->assertNull($form->getFirstError('password'));
    }

    public function testValid()
    {
        $user = $this->faker()->user();
        $form = new LoginForm([
            'email' => $user->getEmail(),
            'password' => $this->faker()->password()
        ]);
        $this->assertTrue($form->validate());
        $this->assertEmpty($form->getErrors());
        $this->assertNull($form->getFirstError('email'));
        $this->assertNull($form->getFirstError('password'));
    }
}
