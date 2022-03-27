<?php

namespace tests\unit\tests;

use ddruganov\Yii2ApiAuth\forms\auth\LoginForm;
use ddruganov\Yii2ApiAuth\forms\auth\RefreshForm;
use tests\unit\BaseUnitTest;
use Yii;

final class RefreshFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $form = new RefreshForm();
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['refreshToken']
        );
    }

    public function testInvalidRefreshToken()
    {
        $form = new RefreshForm([
            'refreshToken' => $this->getFaker()->text()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['refreshToken']
        );
    }

    public function testValidRefreshToken()
    {
        $form = new RefreshForm([
            'refreshToken' => $this->getRefreshToken()
        ]);
        $result = $form->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotNull($result->getData('tokens.access'));
        $this->assertNotNull($result->getData('tokens.refresh'));
    }

    private function getRefreshToken()
    {
        $app = $this->getFaker()->app();
        $password = $this->getFaker()->password();
        $user = $this->getFaker()->userWithAuthenticatePermission($app, $password);
        $form = new LoginForm([
            'email' => $user->getEmail(),
            'password' => $password,
            'appUuid' => $app->getUuid()
        ]);
        return $form->run()->getData('tokens.refresh');
    }
}
