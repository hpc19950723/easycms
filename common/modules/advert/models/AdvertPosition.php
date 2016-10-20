<?php

namespace common\modules\advert\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class AdvertPosition extends \common\modules\core\models\CommonActiveRecord
{
    const STATUS_ACTIVE = 1;
    
    const STATUS_INACTIVE = 0;
    
    public static function tableName() {
        return '{{%advert_position}}';
    }
    
    
    public function extraFields()
    {
        return [
            'adverts'
        ];
    }
    
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',    // 自己根据数据库字段修改
                'updatedAtAttribute' => 'updated_at',    // 自己根据数据库字段修改
                'value' => new Expression('NOW()'),         // 自己根据数据库字段修改
            ]
        ];
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => '名字',
            'identifier' => '标识符',
            'width' => '宽度',
            'height' => '高度',
            'status' => '状态',
            'description' => '描述',
            'advert_qty' => '广告数'
        ];
    }
    
    
    public function getAdverts()
    {
        return $this->hasMany(Advert::className(), ['position_id' => 'position_id'])
                ->where(['status' => Advert::STATUS_ACTIVE])
                ->orderBy(['position' => SORT_ASC]);
    }
    
    
    public static function getStatus()
    {
        return [
            static::STATUS_INACTIVE => '停用',
            static::STATUS_ACTIVE => '启用'
        ];
    }
}