<?php

namespace ddruganov\Yii2ApiAuth\models;

use yii\db\ActiveRecord;

/**
 * @property string $uuid
 * @property string $name
 * @property string $alias
 * @property string $audience
 * @property string $url
 * @property bool $is_default
 */
final class App extends ActiveRecord
{
    public static function tableName()
    {
        return 'app.app';
    }

    public function rules()
    {
        return [
            [['name', 'alias', 'audience', 'url', 'is_default'], 'required'],
            [['name', 'alias', 'audience', 'url'], 'string'],
            [['name', 'alias', 'url'], 'unique'],
            [['is_default'], 'boolean'],
        ];
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

    public function getUrl()
    {
        return $this->url;
    }

    public function isDefault()
    {
        return $this->is_default;
    }

    public static function default()
    {
        return self::findOne([
            'is_default' => true
        ]);
    }
}
