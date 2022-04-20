<?php

namespace ddruganov\Yii2ApiAuth\models\rbac;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiAuth\models\rbac\queries\PermissionQuery;
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

    public static function find(): PermissionQuery
    {
        return new PermissionQuery();
    }

    public function rules()
    {
        return [
            [['app_uuid', 'name', 'description', 'created_at', 'updated_at'], 'safe'],
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
