<?php

namespace ddruganov\Yii2ApiAuth\forms\user;

use ddruganov\Yii2ApiAuth\models\rbac\Role;
use ddruganov\Yii2ApiAuth\models\rbac\UserHasRole;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\forms\Form;
use yii\base\Model;

abstract class BaseForm extends Form
{
    public ?string $email = null;
    public ?string $name = null;
    public ?array $roleIds = null;

    public function rules()
    {
        return [
            [['email', 'name'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['email'], 'email'],
            [['roleIds'], 'default', 'value' => []],
            [['roleIds'], 'each', 'rule' => ['integer']],
            [['roleIds'], 'each', 'rule' => ['exist', 'targetClass' => Role::class, 'targetAttribute' => ['roleIds' => 'id']]],
        ];
    }

    protected function _run(): ExecutionResult
    {
        $model = $this->getModel();
        $model->setAttributes([
            'email' => $this->email,
            'name' => $this->name
        ]);
        $this->setCustomAttributes($model);
        if (!$model->save()) {
            return ExecutionResult::exception('Ошибка сохранения модели');
        }

        $bindings = UserHasRole::find()->byUserId($model->getId())->all();
        foreach ($bindings as $binding) {
            if ($binding->delete() === false) {
                return ExecutionResult::failure(['roleIds' => 'Ошибка удаления привязок к ролям']);
            }
        }

        foreach ($this->roleIds as $roleId) {
            $binding = new UserHasRole([
                'user_id' => $model->getId(),
                'role_id' => $roleId
            ]);
            if (!$binding->save()) {
                return ExecutionResult::failure(['roleIds' => 'Ошибка привязки ролей']);
            }
        }

        return ExecutionResult::success([
            'id' => $model->getId()
        ]);
    }

    protected function getModel()
    {
        return new User();
    }

    protected function setCustomAttributes(Model $model)
    {
    }
}
