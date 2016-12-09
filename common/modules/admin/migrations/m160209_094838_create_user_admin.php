<?php

use yii\db\Migration;

class m160209_094838_create_user_admin extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `user_admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(32) DEFAULT NULL COMMENT '用户名',
  `password` varchar(60) DEFAULT NULL COMMENT '密码',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `mobile` varchar(11) DEFAULT NULL,
  `remark` text,
  `user_type` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '管理员类型\n1 ＝ 超级管理员\n2 ＝ 普通管理员',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '管理员状态，默认 1 ，启用\n0 ＝ 永久停用\n1 ＝ 启用\n2 ＝ 暂时停用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';
INSERT INTO `user_admin` VALUES (1,'admin','\$2y\$13\$w/ofNlpRgUJEgcLH97/mveFUEmn55sU7kkpDH8ABI/C6hzjZ7JieO',NULL,NULL,NULL,1,1,'2016-06-06 07:07:46','2016-06-17 08:32:14');
");
    }

    public function down()
    {
        $this->dropTable('user_admin');
    }
}
