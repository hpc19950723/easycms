<?php

namespace common\modules\user\models;

use common\models\User;
use yii\base\Model;

class UserForm extends Model
{
    
    public $nickname;
    
    public $bio;
    
    public $realName;
    
    public function rules()
    {
        return [
            [['gender', 'user_type', 'grade', 'status'], 'required'],  //必填项规则
            ['status', 'in', 'range' => [User::STATUS_FORBIDDEN, User::STATUS_ACTIVE, User::STATUS_SUSPENDED]],
            ['user_type', 'in', 'range' => [User::USER_TYPE_NORMAL, User::USER_TYPE_GENIUS_RETAIL_INVESTOR, User::USER_TYPE_AFP]],
            ['gender', 'in', 'range' => [User::GENDER_PRIVACY, User::GENDER_MALE, User::GENDER_FEMALE]],
            ['nickname', 'unique'],
            ['grade', 'integer'],
            [['height', 'weight', 'appointment_fee'], 'double'],
            [['id_no'], 'match', 'pattern'=>'/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/', '身份证号格式不正确'],
        ];
    }
}

