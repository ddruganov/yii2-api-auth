<?php

namespace ddruganov\Yii2ApiAuth\models\rbac;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $app_uuid
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
final class Permission extends ActiveRecord
{
    public static function tableName()
    {
        return 'rbac.permission';
    }

    public function rules()
    {
        return [
            [['app_uuid', 'name', 'description', 'created_at', 'updated_at'], 'required'],
            [['app_uuid', 'name', 'description'], 'string'],
            [['app_uuid'], 'exist', 'targetClass' => App::class, 'targetAttribute' => ['app_uuid' => 'uuid']],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d H:i:s']
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

    public function getAppUuid()
    {
        return $this->app_uuid;
    }

    public function getApp()
    {
        return App::findOne($this->getAppUuid());
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
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
