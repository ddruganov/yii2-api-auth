<?php

namespace ddruganov\Yii2ApiAuth\models;

use ddruganov\Yii2ApiAuth\models\queries\AppQuery;
use ddruganov\Yii2ApiEssentials\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property string $uuid
 * @property string $name
 * @property string $alias
 * @property string $audience
 * @property string $base_url
 * @property bool $is_default
 * @property string $created_at
 */
final class App extends ActiveRecord
{
    public static function tableName()
    {
        return 'app.app';
    }

    public static function find(): AppQuery
    {
        return new AppQuery();
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'audience', 'base_url', 'is_default', 'created_at'], 'safe']
        ];
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getAudience()
    {
        return $this->audience;
    }

    public function getBaseUrl()
    {
        return $this->base_url;
    }

    public function isDefault()
    {
        return $this->is_default;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public static function default(): static
    {
        return self::find()->default()->one();
    }
}
