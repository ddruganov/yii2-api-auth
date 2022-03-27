<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use yii\db\Migration;

class m000000_000080_create_access_token_table extends Migration
{
    private function getSchemaName()
    {
        return 'auth';
    }

    private function getTableName()
    {
        return $this->getSchemaName() . '.access_token';
    }

    public function safeUp()
    {
        $this->execute('create schema ' . $this->getSchemaName());

        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey(),
            'value' => $this->text()->notNull(),
            'expires_at' => $this->timestamp()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());
        $this->execute('drop schema ' . $this->getSchemaName());
    }
}
