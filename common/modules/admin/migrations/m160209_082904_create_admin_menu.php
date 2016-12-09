<?php

use yii\db\Migration;

class m160209_082904_create_admin_menu extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('admin_menu', [
            'menu_id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(45)->notNull(),
            'parent_id' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'route' => $this->string(45),
            'icon' => $this->string(45),
            'children_count' => $this->integer()->notNull()->defaultValue(0),
            'position' => $this->integer()->notNull()->defaultValue(1),
            'child_route' => $this->text(),
            'env' => "tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '菜单类型 1;管理员 2;开发者'",
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'"
        ],$tableOptions);
        $this->execute("INSERT INTO `admin_menu` VALUES (12,'密码管理',62,'/admin/account/update-password','',0,20,'',1,'2016-02-02 04:22:46','2016-07-21 04:11:59'),(17,'子账号管理',62,'/admin/account/index','',0,10,'/admin/account/update,/admin/account/create',1,'2016-02-02 06:07:17','2016-07-21 04:12:25'),(62,'系统管理',0,'#','&#xe62e;',5,20,NULL,1,'2016-06-17 07:22:25','2016-06-17 08:47:05'),(63,'会员管理',0,'/user/index/index','&#xe60d;',0,10,'/user/index/create,/user/index/update',1,'2016-06-17 08:46:56','2016-07-19 04:13:56'),(64,'模块管理',0,'/module/index/index','&#xe6bf;',0,30,'/module/index/create',2,'2016-06-21 03:38:58','2016-08-22 06:46:28'),(65,'菜单管理',0,'/admin/admin-menu/index','&#xe667;',0,200,'/admin/admin-menu/update,/admin/admin-menu/create,/admin/admin-menu/view',2,'2016-06-23 06:29:57','2016-07-21 04:11:42'),(66,'角色管理',62,'/admin/role/index','',0,15,'/admin/role/create,/admin/role/view,/admin/role/update',1,'2016-06-23 10:33:57','2016-08-16 06:54:42'),(67,'权限管理',62,'/admin/permission/index','',0,16,'/admin/permission/create,/admin/permission/view,/admin/permission/update',1,'2016-06-23 10:34:48','2016-08-16 06:54:05'),(68,'路由管理',62,'/admin/route/index','',0,17,'',1,'2016-08-16 03:25:00','2016-08-16 03:25:00'),(69,'系统配置',0,'＃','&#xe63c;',2,25,'',1,'2016-08-16 08:57:04','2016-08-16 08:57:18'),(70,'首页设置',69,'/admin/config/user-home','',0,0,'',1,'2016-08-16 08:58:09','2016-08-16 08:58:09'),(71,'意见反馈',0,'/feedback/index/index','&#xe692;',0,40,'/feedback/index/view',1,'2016-08-23 10:07:30','2016-08-23 10:23:48'),(72,'开发者界面',0,'/admin?env=2','&#xe62b;',0,1000,'',1,'2016-08-25 10:03:10','2016-08-25 10:11:05'),(73,'管理员界面',0,'/admin?env=1','&#xe62b;',0,1001,'',2,'2016-08-25 10:04:03','2016-08-25 10:04:03'),(74,'广告管理',0,'#','&#xe6ff;',2,21,'',1,'2016-08-26 08:30:25','2016-08-26 08:31:17'),(75,'广告列表',74,'/advert/index/index','',0,20,'/advert/index/create,/advert/index/update',1,'2016-08-26 08:32:27','2016-09-06 10:08:03'),(76,'广告位管理',74,'/advert/position/index','',0,10,'/advert/position/create',1,'2016-08-26 08:35:30','2016-08-26 10:29:41'),(77,'内容管理',0,'#','&#xe616;',2,22,'',1,'2016-08-30 04:29:53','2016-08-30 04:29:53'),(78,'内容分类',77,'/article/category/index','',0,10,'/article/category/create,/article/category/update',1,'2016-08-30 04:30:43','2016-08-30 04:30:43'),(79,'内容列表',77,'/article/index/index','',0,20,'/article/index/create,/article/index/update',1,'2016-08-30 04:31:38','2016-08-30 04:31:38'),(81,'消息管理',0,'/message/index/index','&#xe6c5;',0,35,'/message/index/create,/message/index/update',1,'2016-09-12 02:07:28','2016-09-12 02:07:28'),(82,'微信配置',69,'/admin/config/user-weixin','',0,20,'',1,'2016-09-20 02:28:48','2016-09-20 02:29:21');");
    }

    public function down()
    {
        $this->dropTable('admin_menu');
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
