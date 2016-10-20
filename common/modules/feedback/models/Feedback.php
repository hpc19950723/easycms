<?php
namespace common\modules\feedback\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\modules\user\models\User;

class Feedback extends \common\modules\core\models\CommonActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
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
            'content' => '内容',
        ];
    }
    
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }
}