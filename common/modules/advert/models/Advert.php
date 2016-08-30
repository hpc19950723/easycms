<?php

namespace common\modules\advert\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\modules\advert\models\AdvertPosition;
use common\modules\core\components\Tools;

class Advert extends \common\modules\core\models\CommonActiveRecord
{
    const STATUS_ACTIVE = 1;
    
    const STATUS_INACTIVE = 0;
    
    public static function tableName() {
        return '{{%advert}}';
    }
    
    
    public function fields()
    {
        return [
            'name',
            'link',
            'image' => function($model) {
                return Tools::getFileUrl($model->image);
            },
            'start_time',
            'end_time',
            'position',
            'status'
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
            'position_id' => '广告位名称',
            'name' => '广告名称',
            'image' => '图片',
            'link' => '链接',
            'status' => '状态',
            'start_time' => '广告上线时间',
            'end_time' => '广告下线时间',
            'position' => '排序'
        ];
    }
    
    
    public static function getStatus()
    {
        return [
            static::STATUS_INACTIVE => '停用',
            static::STATUS_ACTIVE => '启用'
        ];
    }
    
    
    public function getAdvertPosition()
    {
        return $this->hasOne(AdvertPosition::className(), ['position_id' => 'position_id']);
    }
    
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $model = AdvertPosition::findOne($this->position_id);
            if ($model !== null) {
                $model->updateCounters(['advert_qty' => 1]);
            }
        }
    }
    
    
    public function afterDelete()
    {
        $model = AdvertPosition::findOne($this->position_id);
        if ($model !== null) {
            $model->updateCounters(['advert_qty' => -1]);
        }
    }
}