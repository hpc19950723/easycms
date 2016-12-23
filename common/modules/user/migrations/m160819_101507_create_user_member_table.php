<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_member`.
 */
class m160819_101507_create_user_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('user_member', [
            'user_id' => $this->primaryKey()->unsigned(),
            'nickname' => $this->string(65)->comment('昵称'),
            'avatar' => $this->string()->comment('头像'),
            'website' => $this->string()->comment('站点'),
            'bio' => $this->text()->comment('简介'),
            'gender' => "tinyint(1) unsigned DEFAULT '0' COMMENT '性别 0;保密 1;男性 2;女性'",
            'email' => $this->string(),
            'user_group_id' => $this->integer(10)->notNull()->defaultValue(1),
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态 -1;删除 0;停用 1;启用'",
            'access_token' => $this->string(),
            'instagram_user_id' => $this->string(20),
            'instagram_access_token' => $this->string(),
            'auth_key' => $this->string(),
            'login_ip' => $this->integer()->unsigned(),
            'login_at' => $this->datetime(),
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ], $tableOptions);
        
        $this->createTable('user_group',[
            'user_group_id' => $this->primaryKey()->unsigned(),
            'group_name' => $this->string(50)->notNull()->comment('组名称'),
        ], $tableOptions);
        $this->insert('user_group',['group_name' =>'普通用户']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_member');
        $this->dropTable('user_group');
    }
}
