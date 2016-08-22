<?php

namespace common\modules\module\models;

use Yii;
use yii\helpers\ArrayHelper;

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
    static $moduleCacheKey = 'Module_Cache_All';
    
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
            [['status', 'has_api', 'has_admin'], 'integer'],
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
            'has_api' => 'API子模块',
            'has_admin' => 'ADMIN子模块',
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
    
    
    /**
     * 获取所有记录
     */
    public static function getAll()
    {
        $modules = Yii::$app->cache->get(static::$moduleCacheKey);
        
        if(empty($modules)) {
            $modules = static::refreshCache();
        }
        return $modules;
    }
    
    
    public static function refreshCache()
    {
        $modules = static::find()->asArray()->all();
        $modules = ArrayHelper::index($modules, 'name');
        Yii::$app->cache->set(static::$moduleCacheKey, $modules);
        return $modules;
    }
    
    
    public function afterSave($insert, $changedAttributes)
    {
        static::refreshCache();
    }
    
    
    public function afterDelete()
    {
        static::refreshCache();
    }
}
