<?php

namespace common\modules\admin\models;

use Yii;

/**
 * This is the model class for table "module".
 *
 * @property integer $module_id
 * @property string $name
 * @property string $title
 * @property string $dir
 * @property string $settings
 * @property string $version
 * @property integer $status
 */
class Module extends \common\modules\core\models\CommonActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module}}';
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['settings'], 'string'],
            [['status'], 'integer'],
            [['name', 'title', 'version'], 'string', 'max' => 45],
            [['dir'], 'string', 'max' => 255],
        ];
    }

    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'title' => '标题',
            'dir' => '目录',
            'settings' => '设置',
            'version' => '版本号',
            'has_api' => '存在API子模块',
            'has_admin' => '存在ADMIN子模块',
            'status' => '状态',
        ];
    }
    
    
    /**
     * 获取状态列表
     * @return array
     */
    public static function getStatusList()
    {
        return [
            static::STATUS_ACTIVE => '开启',
            static::STATUS_INACTIVE => '关闭',
        ];
    }
}
