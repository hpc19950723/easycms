<?php

use yii\db\Migration;

/**
 * Handles the creation for table `instagram_follow`.
 */
class m161223_031704_create_instagram_follow_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('instagram_follow', [
            'instagram_follow_id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'instagram_user_id' => $this->bigInteger(8)->notNull(), //关注者，即粉丝
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('instagram_follow');
    }
}
