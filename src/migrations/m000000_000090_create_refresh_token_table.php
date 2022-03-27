<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use yii\db\Migration;

class m000000_000090_create_refresh_token_table extends Migration
{
    private function getTableName()
    {
        return 'auth.refresh_token';
    }

    public function safeUp()
    {
        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'app_uuid' => $this->string(36)->notNull(),
            'value' => $this->string(64)->notNull(),
            'access_token_id' => $this->integer()->notNull(),
            'expires_at' => $this->timestamp()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $this->addForeignKey('refresh_token_fk_user', $this->getTableName(), 'user_id', 'user.user', 'id', 'cascade');
        $this->createIndex('user_id_idx', $this->getTableName(), 'user_id');

        $this->addForeignKey('refresh_token_fk_app', $this->getTableName(), 'app_uuid', 'app.app', 'uuid', 'cascade');
        $this->createIndex('app_uuid_idx', $this->getTableName(), 'app_uuid');

        $this->addForeignKey('refresh_token_fk_access_token', $this->getTableName(), 'access_token_id', 'auth.access_token', 'id', 'cascade');
        $this->createIndex('access_token_id_idx', $this->getTableName(), 'access_token_id');
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());
    }
}
