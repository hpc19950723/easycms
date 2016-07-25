<?php

namespace common\modules\user\models\form;

use common\modules\user\models\User;
use common\modules\user\models\SecurityCode;

class ResetPasswordForm extends \yii\base\Model
{
    public $code;
    
    public $mobile;
    
    public $password;
    
    public function rules() {
        return [
            [['mobile', 'code', 'password'], 'required'],
            ['password', 'string', 'min' => 6, 'max' => 32],
            ['code', 'exist', 'targetClass' => 'common\modules\user\models\SecurityCode', 'filter' => function($query) {
                $query->andWhere([
                    'mobile' => $this->mobile,
                    'type' => SecurityCode::TYPE_RESET_PASSWORD
                ])->andWhere([
                    '>=', 'expiration', date('Y-m-d H:i:s')
                ]);
            }, 'message' => '您输入的验证码不正确或验证码已过期'],
            ['mobile', 'match', 'pattern'=>'/^[1][0-9]{10}$/','message' => '手机号格式不正确'],
            ['mobile', 'exist', 'targetClass' => 'common\modules\user\models\User', 'filter' => ['status' => User::STATUS_ACTIVE], 'message' => '您的帐号不存在,或被永久禁用'],
        ];
    }
    
    
    public function save()
    {
        if($this->validate()) {
            $userModel = User::findByMobile($this->mobile);
            $userModel->setPassword($this->password);
            return $userModel->save();
        } else {
            return false;
        }
    }
}