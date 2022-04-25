<?php

namespace tests\unit\tests\auth;

use ddruganov\Yii2ApiAuth\components\AccessTokenProviderInterface;
use ddruganov\Yii2ApiAuth\forms\auth\LoginForm;
use ddruganov\Yii2ApiAuth\forms\auth\LogoutForm;
use tests\components\MockAccessTokenProvider;
use tests\unit\BaseUnitTest;
use Yii;

final class LogoutFormTest extends BaseUnitTest
{
    public function testEmpty()
    {
        $this->getAccessTokenProvider()->setAccessToken(
            $this->getAccessToken()
        );

        $form = new LogoutForm();
        $result = $form->run();
        $this->assertExecutionResultSuccessful($result);
    }

    private function getAccessToken()
    {
        $app = $this->getFaker()->app();
        $password = $this->getFaker()->password();
        $user = $this->getFaker()->userWithAuthenticatePermission($app, $password);
        $form = new LoginForm([
            'email' => $user->getEmail(),
            'password' => $password,
            'appUuid' => $app->getUuid()
        ]);
        return $form->run()->getData('tokens.access');
    }

    private function getAccessTokenProvider(): MockAccessTokenProvider
    {
        return Yii::$app->get(AccessTokenProviderInterface::class);
    }
}
