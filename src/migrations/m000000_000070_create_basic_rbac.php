<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use ddruganov\Yii2ApiAuth\models\App;
use ddruganov\Yii2ApiEssentials\DateHelper;
use yii\db\Migration;
use yii\db\Query;

class m000000_000070_create_basic_rbac extends Migration
{
    private const PERMISSIONS = [
        'authenticate' => 'Вход',

        'app.view' => 'Просмотр приложений',
        'app.create' => 'Создание приложений',
        'app.update' => 'Редактирование приложений',
        'app.delete' => 'Удаление приложений',

        'permission.view' => 'Просмотр разрешений',
        'permission.create' => 'Создание разрешений',
        'permission.update' => 'Редактирование разрешений',
        'permission.delete' => 'Удаление разрешений',

        'role.view' => 'Просмотр ролей',
        'role.create' => 'Создание ролей',
        'role.update' => 'Редактирование ролей',
        'role.delete' => 'Удаление ролей',

        'user.view' => 'Просмотр пользователей',
        'user.create' => 'Создание пользователей',
        'user.update' => 'Редактирование пользователей',
        'user.delete' => 'Удаление пользователей',
    ];

    public function safeUp()
    {
        $this->insert('rbac.role', [
            'name' => 'admin',
            'description' => 'Admin',
            'created_at' => DateHelper::now(),
            'updated_at' => DateHelper::now()
        ]);
        $roleId = $this->getDb()->getLastInsertID();

        foreach (self::PERMISSIONS as $name => $description) {
            $this->insert('rbac.permission', [
                'app_uuid' => App::default()->getUuid(),
                'name' => $name,
                'description' => $description,
                'created_at' => DateHelper::now(),
                'updated_at' => DateHelper::now()
            ]);
            $permissionId = $this->getDb()->getLastInsertID();

            $this->insert('rbac.role_has_permission', [
                'role_id' => $roleId,
                'permission_id' => $permissionId
            ]);
        }

        $userId = (new Query())
            ->select(['id'])
            ->from('user.user')
            ->where(['email' => 'admin@yourdomain.com'])
            ->scalar();
        $this->insert('rbac.user_has_role', [
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }

    public function safeDown()
    {
        $this->delete('rbac.role', ['name' => 'admin']);

        foreach (array_keys(self::PERMISSIONS) as $name) {
            $this->delete('rbac.permission', ['name' => $name]);
        }
    }
}
