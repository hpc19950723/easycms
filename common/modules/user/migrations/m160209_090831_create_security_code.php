<?php

use yii\db\Migration;

class m160209_090831_create_security_code extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `security_code` (
  `security_code_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `content` varchar(255) NOT NULL,
  `expiration` datetime NOT NULL,
  `type` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`security_code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('security_code');
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
