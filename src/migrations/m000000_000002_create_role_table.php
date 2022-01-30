<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use yii\db\Migration;

class m000000_000002_create_role_table extends Migration
{
    private function getSchemaName()
    {
        return 'rbac';
    }

    private function getTableName()
    {
        return $this->getSchemaName() . '.role';
    }

    public function safeUp()
    {
        $this->execute('create schema ' . $this->getSchemaName());

        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey()->unique(),
            'name' => $this->string()->unique()->notNull(),
            'description' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());
        $this->execute('drop schema ' . $this->getSchemaName());

        return true;
    }
}
