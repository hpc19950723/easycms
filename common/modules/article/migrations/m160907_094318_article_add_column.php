<?php

use yii\db\Migration;

class m160907_094318_article_add_column extends Migration
{
    public function up()
    {
        $this->addColumn('article', 'identifier', $this->string(45)->comment('唯一标识符,仅可包含英文,下划线(_),数字')->after('article_id'));
    }

    public function down()
    {
        $this->dropColumn('article', 'identifier');
    }
}
