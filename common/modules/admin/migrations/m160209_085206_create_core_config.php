<?php

use yii\db\Migration;

class m160209_085206_create_core_config extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('core_config', [
            'config_id' => $this->primaryKey()->unsigned(),
            'path' => $this->string()->notNull()->comment('配置路径'),
            'value' => $this->text()->comment('配置值')
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('core_config');
    }
}
