<?php

namespace tests\unit\tests\auth;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\forms\auth\LoginIntoForm;
use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\User;
use tests\components\MockAuthComponent;
use tests\unit\BaseUnitTest;
use Yii;

final class LoginIntoFormTest extends BaseUnitTest
{
    private App $app;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->getFaker()->app();
        $this->user = $this->getFaker()->userWithAuthenticatePermission($this->app);
        $this->getAuth()->setCurrentUser($this->user);
    }

    private function getAuth(): MockAuthComponent
    {
        return Yii::$app->get(AuthComponentInterface::class);
    }

    public function testEmpty()
    {
        $form = new LoginIntoForm();
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['appUuid']
        );
    }

    public function testInvalidAppUuid()
    {
        $form = new LoginIntoForm([
            'appUuid' => $this->getFaker()->uuid()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['appUuid']
        );
    }

    public function testNoAuthenticationPermission()
    {
        $form = new LoginIntoForm([
            'appUuid' => $this->getFaker()->app()->getUuid()
        ]);
        $result = $form->run();
        $this->assertExecutionResultException($result);
    }

    public function testValid()
    {
        $form = new LoginIntoForm([
            'appUuid' => $this->app->getUuid()
        ]);
        $result = $form->run();
        $this->assertExecutionResultSuccessful($result);
        $this->assertNotNull($result->getData('tokens.access'));
        $this->assertNotNull($result->getData('tokens.refresh'));
    }
}
