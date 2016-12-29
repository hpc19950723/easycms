<?php

namespace common\modules\instagram\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class InstagramUser extends \common\modules\core\models\CommonActiveRecord
{
    public static function tableName() {
        return '{{%instagram_user}}';
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
}