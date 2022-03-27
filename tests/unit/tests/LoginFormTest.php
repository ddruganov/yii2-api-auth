<?php

namespace tests\unit\tests;

use ddruganov\Yii2ApiAuth\forms\auth\LoginForm;
use tests\unit\BaseUnitTest;
use Yii;

final class LoginFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new LoginForm();
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'password', 'appUuid']
        );
    }

    public function testInvalidEmail()
    {
        $form = new LoginForm([
            'email' => $this->getFaker()->email()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'password', 'appUuid']
        );
    }

    public function testValidEmail()
    {
        $user = $this->getFaker()->user();
        $form = new LoginForm([
            'email' => $user->getEmail()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['password', 'appUuid'],
            noErrorKeys: ['email']
        );
    }

    public function testPasswordOnly()
    {
        $form = new LoginForm([
            'password' => $this->getFaker()->password()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'appUuid'],
            noErrorKeys: ['password'],
        );
    }

    public function testInvalidAppUuid()
    {
        $form = new LoginForm([
            'appUuid' => $this->getFaker()->uuid()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'password', 'appUuid']
        );
    }

    public function testValidAppUuid()
    {
        $form = new LoginForm([
            'appUuid' => $this->getFaker()->app()->getUuid()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['email', 'password'],
            noErrorKeys: ['appUuid']
        );
    }

    public function testValid()
    {
        $app = $this->getFaker()->app();
        $password = $this->getFaker()->password();
        $user = $this->getFaker()->userWithAuthenticatePermission($app, $password);
        $form = new LoginForm([
            'email' => $user->getEmail(),
            'password' => $password,
            'appUuid' => $app->getUuid()
        ]);
        $result = $form->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotNull($result->getData('tokens.access'));
        $this->assertNotNull($result->getData('tokens.refresh'));
    }

    public function testMasterPassword()
    {
        $masterPassword = Yii::$app->params['authentication']['masterPassword']['value'];
        $app = $this->getFaker()->app();
        $user = $this->getFaker()->userWithAuthenticatePermission($app);
        $form = new LoginForm([
            'email' => $user->getEmail(),
            'password' => $masterPassword,
            'appUuid' => $app->getUuid()
        ]);
        $result = $form->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotNull($result->getData('tokens.access'));
        $this->assertNotNull($result->getData('tokens.refresh'));
    }
}
