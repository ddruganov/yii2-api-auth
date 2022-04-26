<?php

namespace ddruganov\Yii2ApiAuth\models;

use ddruganov\Yii2ApiAuth\models\queries\UserQuery;
use ddruganov\Yii2ApiEssentials\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 */
class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'user.user';
    }

    public static function find(): UserQuery
    {
        return new UserQuery(static::class);
    }

    public function rules()
    {
        return [
            [['email', 'name', 'password', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
