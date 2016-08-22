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
        $this->createTable('user_member', [
            'user_id' => $this->primaryKey(),
            'mobile' => $this->string(11)->notNull()->unique(),
            'password' => $this->string(60)->comment('密码'),
            'password_reset_token' => $this->string(),
            'nickname' => $this->string(65)->comment('昵称'),
            'avatar' => $this->string()->comment('头像'),
            'bio' => $this->text()->comment('简介'),
            'real_name' => $this->string()->comment('姓名'),
            'gender' => "tinyint(1) unsigned DEFAULT '0' COMMENT '性别 0;保密 1;男性 2;女性'",
            'email' => $this->string(),
            'qq' => $this->string(),
            'wechat' => $this->string(),
            'id_no' => $this->string(18)->comment('身份证号'),
            'user_type' => "tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户类型 1;普通'",
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态 -1;删除 0;停用 1;启用'",
            'access_token' => $this->string(),
            'wechat_oauth' => $this->string(),
            'weibo_oauth' => $this->string(),
            'qq_oauth' => $this->string(),
            'auth_key' => $this->string(),
            'login_ip' => $this->integer(),
            'login_at' => $this->datetime(),
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_member');
    }
}
