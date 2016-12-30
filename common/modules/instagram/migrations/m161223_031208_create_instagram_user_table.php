<?php

use yii\db\Migration;

/**
 * Handles the creation for table `instagram_user`.
 */
class m161223_031208_create_instagram_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('instagram_user', [
            'id' => $this->primaryKey(),
            'instagram_user_id' => $this->bigInteger(8)->unsigned()->unique(),
            'username' => $this->string(40)->notNull(),
            'full_name' => $this->string(40)->notNull(),
            'profile_picture' => $this->string(255)->notNull(),
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('instagram_user');
    }
}
