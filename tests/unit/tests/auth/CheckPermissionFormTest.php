<?php

namespace tests\unit\tests\auth;

use ddruganov\Yii2ApiAuth\components\AccessTokenProviderInterface;
use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\forms\auth\CheckPermissionForm;
use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use tests\components\MockAccessTokenProvider;
use tests\unit\BaseUnitTest;
use Yii;

final class CheckPermissionFormTest extends BaseUnitTest
{
    private App $app;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->getFaker()->app();
        $this->user = $this->getFaker()->userWithAuthenticatePermission($this->app);

        $loginResult = $this->getAuth()->login($this->user, $this->app);
        $this->getAccessTokenProvider()->setAccessToken($loginResult->getData('tokens.access'));
    }

    public function testEmpty()
    {
        $form = new CheckPermissionForm();
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['permissionName']
        );
    }

    public function testInvalidPermissionName()
    {
        $form = new CheckPermissionForm([
            'permissionName' => $this->getFaker()->word()
        ]);
        $result = $form->run();
        $this->assertExecutionResultErrors(
            result: $result,
            errorKeys: ['permissionName']
        );
    }

    public function testValidPermissionName()
    {
        $permission = $this->getFaker()->permission($this->getFaker()->word(), $this->app);
        $form = new CheckPermissionForm([
            'permissionName' => $permission->getName()
        ]);
        $result = $form->run();
        $this->assertExecutionResultException($result);
    }

    public function testWithUserHavingPermission()
    {
        $permission = $this->getFaker()->permission($this->getFaker()->word(), $this->app);
        $role = $this->getFaker()->role($this->getFaker()->word(), [$permission->getId()]);
        (new UserHasRole(['user_id' => $this->user->getId(), 'role_id' => $role->getId()]))->save();

        $form = new CheckPermissionForm([
            'permissionName' => $permission->getName()
        ]);
        $result = $form->run();
        $this->assertExecutionResultSuccessful($result);
    }

    private function getAuth(): AuthComponentInterface
    {
        return Yii::$app->get(AuthComponentInterface::class);
    }

    private function getAccessTokenProvider(): MockAccessTokenProvider
    {
        return Yii::$app->get(AccessTokenProviderInterface::class);
    }
}
