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
        $this->createTable('instagram_follow', [
            'instagram_follow_id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'instagram_user_id' => $this->string(20)->notNull() //关注者，即粉丝
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('instagram_follow');
    }
}
