<?php

use yii\db\Migration;

class m160920_102331_alter_security_code_table extends Migration
{
    public function up()
    {
        $this->alterColumn('security_code', 'type', 'string(20) not null');
    }

    public function down()
    {
        echo "m160920_102331_alter_user_member_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
