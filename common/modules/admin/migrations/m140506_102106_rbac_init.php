<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

/**
 * Initializes RBAC tables
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m140506_102106_rbac_init extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
        
        $this->execute("INSERT INTO `admin_auth_item` VALUES ('/admin/*',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/account/*',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/account/delete',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/account/index',2,NULL,NULL,NULL,1471319195,1471319195),('/admin/account/update',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/account/update-password',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/admin-menu/*',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/admin-menu/create',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/admin-menu/delete',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/admin-menu/index',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/admin-menu/update',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/admin-menu/view',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/config/*',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/config/user-home',2,NULL,NULL,NULL,1471337960,1471337960),('/admin/index/*',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/index/error',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/index/index',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/index/login',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/index/logout',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/module/*',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/module/create',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/module/index',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/module/update-status',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/permission/*',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/permission/create',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/permission/delete',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/permission/index',2,NULL,NULL,NULL,1471331969,1471331969),('/admin/permission/role',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/permission/update',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/permission/view',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/role/*',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/role/create',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/role/delete',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/role/index',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/role/role',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/role/update',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/role/view',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/route/*',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/route/assign',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/route/create',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/route/index',2,NULL,NULL,NULL,1471331970,1471331970),('/admin/route/refresh',2,NULL,NULL,NULL,1471331970,1471331970),('/feedback/*',2,NULL,NULL,NULL,1472178177,1472178177),('/user/*',2,NULL,NULL,NULL,1471331970,1471331970),('/user/index/*',2,NULL,NULL,NULL,1471331970,1471331970),('/user/index/create',2,NULL,NULL,NULL,1471331970,1471331970),('/user/index/delete',2,NULL,NULL,NULL,1471331970,1471331970),('/user/index/index',2,NULL,NULL,NULL,1471331970,1471331970),('/user/index/update',2,NULL,NULL,NULL,1471331970,1471331970),('会员管理',2,'会员管理',NULL,NULL,1471332234,1471338026),('子账号管理',2,'子账号管理',NULL,NULL,1471328198,1471332180),('密码管理',2,'密码管理',NULL,NULL,1471332202,1471332202),('意见反馈',2,'意见反馈',NULL,NULL,1472178199,1472178199),('普通管理员',1,'普通管理员',NULL,NULL,1471332486,1472185889);");
        $this->execute("INSERT INTO `admin_auth_item_child` VALUES ('子账号管理','/admin/account/delete'),('子账号管理','/admin/account/index'),('子账号管理','/admin/account/update'),('密码管理','/admin/account/update-password'),('会员管理','/admin/config/user-home'),('意见反馈','/feedback/*'),('会员管理','/user/index/create'),('会员管理','/user/index/delete'),('会员管理','/user/index/index'),('会员管理','/user/index/update'),('普通管理员','会员管理'),('普通管理员','子账号管理'),('普通管理员','意见反馈');");
        $this->execute("INSERT INTO `admin_auth_assignment` VALUES ('普通管理员','4',1471332566),('普通管理员','5',1472713447);");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }
}