<?php

use yii\db\Migration;

/**
 * Handles the creation for table `advert_position`.
 */
class m160826_073038_create_advert_advert_position_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('advert_position', [
            'position_id' => $this->primaryKey(),
            'name' => $this->string(60)->notNull(),
            'identifier' => $this->string(45)->notNull()->unique(),
            'width' => $this->smallInteger(5)->defaultValue(0)->notNull(),
            'height' => $this->smallInteger(5)->defaultValue(0)->notNull(),
            'description' => $this->string(255),
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0;停用 1;启用'",
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ]);
        $this->addCommentOnTable('advert_position', '广告版位表');
        $this->addCommentOnColumn('advert_position', 'identifier', '唯一标识符,仅可包含英文,下划线(_),数字');
        
        $this->createTable('advert', [
            'advert_id' => $this->primaryKey(),
            'position_id' => $this->integer(11)->notNull(),
            'name' => $this->string(60)->notNull(),
            'link' => $this->string(),
            'image' => $this->string(),
            'start_time' => $this->dateTime(),
            'end_time' => $this->dateTime(),
            'position' => $this->smallInteger(5)->defaultValue(0),
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0;停用 1;启用'",
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ]);
        $this->addCommentOnTable('advert', '广告表');
        $this->addCommentOnColumn('advert', 'name', '名称');
        $this->addCommentOnColumn('advert', 'link', '链接');
        $this->addCommentOnColumn('advert', 'image', '图片');
        $this->addCommentOnColumn('advert', 'start_time', '广告上线时间');
        $this->addCommentOnColumn('advert', 'end_time', '广告下线时间');
        $this->addCommentOnColumn('advert', 'position', '排序');
        $this->addForeignKey('FK_ADVERT_ADVERT_POSITION_POSITION_ID', 'advert', 'position_id', 'advert_position', 'position_id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('advert');
        $this->dropTable('advert_position');
    }
}
