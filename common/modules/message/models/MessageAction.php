<?php

namespace common\modules\message\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


class MessageAction extends \common\modules\core\models\CommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_action}}';
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
