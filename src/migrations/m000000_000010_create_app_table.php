<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use yii\db\Expression;
use yii\db\Migration;

class m000000_000010_create_app_table extends Migration
{
    private function getSchemaName()
    {
        return 'app';
    }

    private function getTableName()
    {
        return "{$this->getSchemaName()}.app";
    }

    public function safeUp()
    {
        $this->execute("create schema {$this->getSchemaName()}");

        $this->createTable($this->getTableName(), [
            'id' => $this->string()->notNull()->defaultValue(new Expression('gen_random_uuid()')),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'audience' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'is_default' => $this->boolean()->notNull()->defaultValue(false)
        ]);

        $this->addPrimaryKey('app_pk', $this->getTableName(), 'id');

        $this->insert($this->getTableName(), [
            'name' => 'default',
            'alias' => 'default',
            'audience' => 'localhost',
            'url' => 'localhost',
            'is_default' => true
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());

        $this->execute("drop schema {$this->getSchemaName()}");
    }
}
