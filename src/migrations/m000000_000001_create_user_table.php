<?php

use ddruganov\Yii2ApiEssentials\DateHelper;
use yii\db\Migration;

class m000000_000001_create_user_table extends Migration
{
    private function getSchemaName()
    {
        return '"user"';
    }

    private function getTableName()
    {
        return $this->getSchemaName() . '.user';
    }

    public function safeUp()
    {
        $this->execute('create schema ' . $this->getSchemaName());

        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey()->unique(),
            'email' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'is_banned' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $this->insert($this->getTableName(), [
            'email' => 'admin@yourdomain.com',
            'name' => 'Admin',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'created_at' => DateHelper::now(),
            'updated_at' => DateHelper::now(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());
        $this->execute('drop schema ' . $this->getSchemaName());

        return true;
    }
}
