<?php

namespace common\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\modules\core\components\Tools;

class SecurityCode extends \common\modules\core\models\CommonActiveRecord
{
    //验证码类型常量
    const TYPE_REGISTER = 1;
    
    const TYPE_RESET_PASSWORD = 2;
    
    const TYPE_LOGIN = 3;

    public static function tableName() {
        return '{{%security_code}}';
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
    
    
    public function rules() {
        return [
            ['expiration', 'default', 'value' => date('Y-m-d H:i:s', time() + Tools::getModuleParams('user', ['securityCode', 'expiration']))]
        ];
    }
}