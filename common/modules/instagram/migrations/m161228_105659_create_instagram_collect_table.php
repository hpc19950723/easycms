<?php

use yii\db\Migration;

/**
 * Handles the creation for table `instagram_collect`.
 */
class m161228_105659_create_instagram_collect_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('instagram_collect', [
            'instagram_collect_id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'instagram_user_id' => $this->string(20)->notNull() //关注者，即粉丝
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('instagram_collect');
    }
}
