<?php

namespace common\modules\admin\models;

use Yii;
use common\modules\core\models\CommonActiveRecord;
use yii\helpers\ArrayHelper;

class CoreConfig extends CommonActiveRecord
{
    static $coreConfigAllCacheKey = 'Core_Config_All';
    
    public static function tableName() {
        return '{{%core_config}}';
    }
    
    
    public static function getAll()
    {
        $coreConfig = Yii::$app->cache->get(static::$coreConfigAllCacheKey);
        
        if(empty($coreConfig)) {
            $coreConfig = static::cacheAll();
        }
        return $coreConfig;
    }
    
    
    public static function cacheAll()
    {
        $models = static::find()->all();

        $coreConfig = [];
        foreach($models as $model) {
            $coreConfig[] = $model->toArray();
        }
        $coreConfig = ArrayHelper::index($coreConfig, 'path');
        Yii::$app->cache->set(static::$coreConfigAllCacheKey, $coreConfig);
        return $coreConfig;
    }
    
    
    /**
     * 获取单条配置
     * @param type $path
     * @param type $default
     */
    public static function getConfig($path, $default = null)
    {
        if(isset(static::getAll()[$path])) {
            return static::getAll()[$path]['value'];
        } else {
            return $default;
        }
    }
}