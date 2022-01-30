<?php

namespace ddruganov\Yii2ApiAuth\models\token;

use ddruganov\Yii2ApiEssentials\common\behaviors\TimestampBehavior;
use ddruganov\Yii2ApiEssentials\common\DateHelper;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $value
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 */
class AccessToken extends ActiveRecord
{
    public static function tableName()
    {
        return 'auth.access_token';
    }

    public function rules()
    {
        return [
            [['value', 'expires_at', 'created_at', 'updated_at'], 'required'],
            [['value', 'expires_at', 'created_at', 'updated_at'], 'string'],
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

    public function getValue()
    {
        return $this->value;
    }

    public function expire()
    {
        $this->expires_at = DateHelper::now();
        return $this->save();
    }

    public function isExpired()
    {
        return $this->expires_at < DateHelper::now();
    }
}
