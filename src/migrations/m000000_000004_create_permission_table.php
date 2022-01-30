<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use yii\db\Migration;

class m000000_000004_create_permission_table extends Migration
{
    private function getTableName()
    {
        return 'rbac.permission';
    }

    public function safeUp()
    {
        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey()->unique(),
            'name' => $this->string()->unique()->notNull(),
            'description' => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());

        return true;
    }
}
