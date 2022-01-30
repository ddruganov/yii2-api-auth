<?php

use yii\db\Migration;

class m000000_000003_create_user_has_role_table extends Migration
{
    private function getTableName()
    {
        return 'rbac.user_has_role';
    }

    public function safeUp()
    {
        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey()->unique(),
            'user_id' => $this->integer(),
            'role_id' => $this->integer(),
        ]);

        $this->createIndex('user_and_role_unique', $this->getTableName(), ['user_id', 'role_id'], true);
        $this->addForeignKey('user_has_role_fk_user', $this->getTableName(), 'user_id', 'user.user', 'id', 'cascade');
        $this->addForeignKey('user_has_role_fk_role', $this->getTableName(), 'role_id', 'rbac.role', 'id', 'cascade');
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());

        return true;
    }
}
