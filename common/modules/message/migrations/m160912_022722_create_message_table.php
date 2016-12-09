<?php

use yii\db\Migration;

/**
 * Handles the creation for table `message`.
 */
class m160912_022722_create_message_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('message', [
            'message_id' => $this->primaryKey()->unsigned(),
            'sender_id' => $this->integer()->notNull()->comment('发送人ID 0;系统 >0;用户'),
            'receiver_id' => $this->integer()->notNull()->comment('接收者ID 0;所有人 >0;用户ID'),
            'title' => $this->string()->notNull()->comment('标题'),
            'content' => $this->text()->notNull()->comment('内容'),
            'image' => $this->string()->comment('图片'),
            'type' => "tinyint(1) NOT NULL COMMENT '消息类型'",
            'is_newest' => "tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否为最新消息'",
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ], $tableOptions);
        
        
        $this->createTable('message_action', [
            'message_action_id' => $this->primaryKey()->unsigned(),
            'message_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_read' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否已读'",
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('message');
        $this->dropTable('message_action');
    }
}
