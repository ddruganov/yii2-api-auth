<?php

use yii\db\Migration;

class m000000_000005_create_role_has_permission_table extends Migration
{
    private function getTableName()
    {
        return 'rbac.role_has_permission';
    }

    public function safeUp()
    {
        $this->createTable($this->getTableName(), [
            'id' => $this->primaryKey()->unique(),
            'role_id' => $this->integer()->notNull(),
            'permission_id' => $this->integer()->notNull()
        ]);

        $this->createIndex('role_and_permission_unique', $this->getTableName(), ['role_id', 'permission_id'], true);
        $this->addForeignKey('role_has_permission_fk_role', $this->getTableName(), 'role_id', 'rbac.role', 'id', 'cascade');
        $this->addForeignKey('role_has_permission_fk_permission', $this->getTableName(), 'permission_id', 'rbac.permission', 'id', 'cascade');
    }

    public function safeDown()
    {
        $this->dropTable($this->getTableName());

        return true;
    }
}
