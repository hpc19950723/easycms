<?php

use yii\db\Migration;

class m160209_085510_create_module extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('module', [
            'module_id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(45),
            'title' => $this->string(45),
            'dir' => $this->string(),
            'settings' => $this->text(),
            'version' => $this->string(45),
            'enabled_api' => "tinyint(1) unsigned DEFAULT '0' COMMENT '开启api模块'",
            'enabled_admin' => "tinyint(1) unsigned DEFAULT '0' COMMENT '开启admin模块'",
            'deletable' => "tinyint(1) unsigned DEFAULT '1' COMMENT '是否可删除 1，可删除 2，禁止删除'",
            'status' => "tinyint(1) unsigned DEFAULT '1'"
        ], $tableOptions);
        
        $this->execute("INSERT INTO `module` VALUES (1,'admin','后台模块','common\\modules\\admin',NULL,'1.0.0',0,1,0,1),(2,'module','模块管理',NULL,NULL,'1.0.0',0,1,1,1),(3,'user','用户模块','common\\modules\\user','','1.0.0',1,1,0,1),(4,'feedback','意见反馈',NULL,NULL,'1.0.0',1,1,1,1),(5,'advert','广告管理',NULL,NULL,'1.0.0',1,1,1,1),(19,'article','内容管理','','','1.0.0',1,1,1,1),(20,'message','消息管理',NULL,NULL,'1.0.0',1,1,1,1);");
    }

    public function down()
    {
        $this->dropTable('module');
    }
}
