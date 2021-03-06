<?php

namespace ddruganov\Yii2ApiAuth\http\filters;

use ddruganov\Yii2ApiAuth\components\AuthComponentInterface;
use ddruganov\Yii2ApiAuth\components\RbacComponentInterface;
use ddruganov\Yii2ApiAuth\exeptions\PermissionDeniedException;
use ddruganov\Yii2ApiAuth\models\rbac\Permission;
use ddruganov\Yii2ApiEssentials\exceptions\ModelNotFoundException;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use Throwable;
use Yii;
use yii\base\ActionFilter;

final class RbacFilter extends ActionFilter
{
    public array $rules = [];
    public array $exceptions = [];

    public function beforeAction($action)
    {
        if (in_array($this->getActionId($action), $this->exceptions)) {
            return parent::beforeAction($action);
        }

        try {
            /** @var \ddruganov\Yii2ApiAuth\components\AuthComponent */
            $auth = Yii::$app->get(AuthComponentInterface::class);

            $permissionName = $this->rules[$this->getActionId($action)] ?? null;
            $permission = Permission::findOne([
                'app_uuid' => $auth->getCurrentApp()->getUuid(),
                'name' => $permissionName
            ]);
            if (!$permission) {
                throw new ModelNotFoundException(Permission::class);
            }

            $user = $auth->getCurrentUser();
            if (!$user) {
                throw new PermissionDeniedException();
            }

            if (!Yii::$app->get(RbacComponentInterface::class)->checkPermission($permission, $user)) {
                throw new PermissionDeniedException();
            }
        } catch (Throwable $t) {
            $isPermissionDeniedException = $t instanceof PermissionDeniedException;
            if (!$isPermissionDeniedException) {
                throw $t;
            }

            Yii::$app->getResponse()->data = ExecutionResult::exception($t->getMessage());
            Yii::$app->getResponse()->setStatusCode(403);
            Yii::$app->end();
        }

        return parent::beforeAction($action);
    }
}
