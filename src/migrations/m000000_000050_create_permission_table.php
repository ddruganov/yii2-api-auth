<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use yii\db\Migration;

class m000000_000050_create_permission_table extends Migration
{
    private function getTableName()
    {
        return 'rbac.permission';
    }

    public function safeUp()
    {
        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey()->unique(),
            'app_id' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $this->addForeignKey('fk_app', $this->getTableName(), 'app_id', 'app.app', 'id', 'cascade');
        $this->createIndex('app_id_name_idx', $this->getTableName(), ['app_id', 'name'], true);
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());

        return true;
    }
}
