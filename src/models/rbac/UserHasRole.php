<?php

namespace ddruganov\Yii2ApiAuth\models\rbac;

use ddruganov\Yii2ApiAuth\models\User;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 */
class UserHasRole extends ActiveRecord
{
    public static function tableName()
    {
        return 'rbac.user_has_role';
    }

    public function rules()
    {
        return [
            [['user_id', 'role_id'], 'required'],
            [['user_id', 'role_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUser()
    {
        return User::findOne($this->getUserId());
    }

    public function getRoleId()
    {
        return $this->role_id;
    }

    public function getRole()
    {
        return Role::findOne($this->getRoleId());
    }
}
