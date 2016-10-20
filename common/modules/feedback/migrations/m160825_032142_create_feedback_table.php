<?php

use yii\db\Migration;

/**
 * Handles the creation for table `feedback`.
 */
class m160825_032142_create_feedback_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('feedback', [
            'feedback_id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('feedback');
    }
}
