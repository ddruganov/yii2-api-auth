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
            [['name', 'alias', 'audience', 'base_url', 'created_at'], 'required'],
            [['name', 'alias', 'audience', 'base_url', 'created_at'], 'string'],
            [['name', 'alias', 'base_url', 'is_default'], 'unique'],
            [['is_default'], 'boolean'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d H:i:s']
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

    public static function default()
    {
        return self::findOne([
            'is_default' => true
        ]);
    }
}
