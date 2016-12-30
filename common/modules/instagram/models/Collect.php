<?php

namespace common\modules\instagram\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\modules\instagram\models\InstagramUser;

class Collect extends \common\modules\core\models\CommonActiveRecord
{
    public static function tableName() {
        return '{{%instagram_collect}}';
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
    
    public function getInstagramUser()
    {
        return $this->hasOne(InstagramUser::className(), ['instagram_user_id' => 'instagram_user_id']);
    }
}