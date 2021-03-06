<?php

namespace ddruganov\Yii2ApiAuth\models\token;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\token\queries\RefreshTokenQuery;
use ddruganov\Yii2ApiAuth\models\User;
use ddruganov\Yii2ApiEssentials\behaviors\TimestampBehavior;
use ddruganov\Yii2ApiEssentials\DateHelper;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property string $app_uuid
 * @property string $value
 * @property int $access_token_id
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 */
final class RefreshToken extends ActiveRecord
{
    public static function tableName()
    {
        return 'auth.refresh_token';
    }

    public static function find(): RefreshTokenQuery
    {
        return new RefreshTokenQuery();
    }

    public function rules()
    {
        return [
            [['user_id', 'app_uuid', 'value', 'access_token_id', 'expires_at', 'created_at', 'updated_at'], 'required'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['app_uuid'], 'exist', 'targetClass' => App::class, 'targetAttribute' => ['app_uuid' => 'uuid']],
            [['value'], 'string', 'length' => 64],
            [['access_token_id'], 'exist', 'targetClass' => AccessToken::class, 'targetAttribute' => ['access_token_id' => 'id']],
            [['expires_at', 'created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
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

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUser()
    {
        return User::findOne($this->getUserId());
    }

    public function getAppUuid()
    {
        return $this->app_uuid;
    }

    public function getApp()
    {
        return App::findOne($this->getAppUuid());
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getAccessTokenId()
    {
        return $this->access_token_id;
    }

    public function getAccessToken()
    {
        return AccessToken::findOne($this->getAccessTokenId());
    }

    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function isExpired()
    {
        return $this->expires_at < DateHelper::now();
    }

    public function expire()
    {
        $this->setAttributes([
            'expires_at' => DateHelper::now()
        ]);
        return $this->save() && $this->getAccessToken()->expire();
    }
}
