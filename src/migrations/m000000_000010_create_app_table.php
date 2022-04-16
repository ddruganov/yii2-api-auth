<?php

namespace ddruganov\Yii2ApiAuth\migrations;

use ddruganov\Yii2ApiEssentials\DateHelper;
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
            'uuid' => $this->string()->notNull()->defaultValue(new Expression('gen_random_uuid()')),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'audience' => $this->string()->notNull(),
            'base_url' => $this->string()->notNull(),
            'is_default' => $this->boolean()->null()->defaultValue(null)->unique(),
            'created_at' => $this->timestamp()->notNull()
        ]);

        $this->addPrimaryKey('app_pk', $this->getTableName(), 'uuid');

        $this->insert($this->getTableName(), [
            'name' => 'default',
            'alias' => 'default',
            'audience' => 'localhost',
            'base_url' => 'localhost',
            'is_default' => true,
            'created_at' => DateHelper::now()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());

        $this->execute("drop schema {$this->getSchemaName()}");
    }
}
