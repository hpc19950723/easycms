<?php

use yii\db\Migration;

/**
 * Handles the creation for table `article`.
 */
class m160830_070647_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'category_id' => $this->primaryKey(),
            'identifier' => $this->string(45)->notNull()->unique(),
            'parent_id' => $this->integer(10)->notNull()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'position' => $this->smallInteger(5)->notNull()->defaultValue(0),
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态 0;停用 1;启用'",
            'children_count' => $this->integer(10)->notNull()->defaultValue(0)->comment('子类数量'),
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ]);
        $this->addCommentOnTable('article_category', '文章分类表');
        $this->addCommentOnColumn('article_category', 'identifier', '唯一标识符,仅可包含英文,下划线(_),数字');
        $this->addCommentOnColumn('article_category', 'parent_id', '父类ID');
        $this->addCommentOnColumn('article_category', 'name', '分类名');
        $this->addCommentOnColumn('article_category', 'description', '分类描述');
        $this->addCommentOnColumn('article_category', 'position', '排序');

        $this->createTable('article', [
            'article_id' => $this->primaryKey(),
            'category_id' => $this->integer(10)->notNull(),
            'title' => $this->string()->notNull(),
            'image' => $this->string(),
            'content' => $this->text(),
            'link' => $this->string(),
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态 0;停用 1;启用'",
            'created_at' => "timestamp NULL DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "timestamp NULL DEFAULT NULL COMMENT '更新时间'",
        ]);
        $this->addCommentOnTable('article', '文章表');
        $this->addCommentOnColumn('article', 'title', '文章标题');
        $this->addCommentOnColumn('article', 'image', '文章图片');
        $this->addCommentOnColumn('article', 'content', '内容');
        $this->addCommentOnColumn('article', 'link', '外部链接');
        $this->addForeignKey('FK_ATICLE_ARTICLE_CATEGORY_CATEGORY_ID', 'article', 'category_id', 'article_category', 'category_id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
        $this->dropTable('article_category');
    }
}
