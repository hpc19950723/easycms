<?php

namespace common\modules\user\models\forms;

use Yii;
use yii\base\Model;
use common\modules\user\models\User;
use common\modules\core\components\Tools;
use common\modules\user\models\SecurityCode;
use GuzzleHttp\Client;
use yii\web\HttpException;

class ThirdPartLoginForm extends Model
{
    public $avatar; //头像URl
    
    public $nickname;
    
    public $gender;
    
    public $instagram_user_id;    //第三方登录唯一ID
    
    public $instagram_access_token;
    
    public $code; //验证码
    
    private $_user;
    
    const SCENARIOS_INSTAGRAM_LOGIN = 'instagram_login';
    
    public function rules()
    {
        return [
            [['instagram_user_id', 'instagram_access_token'], 'required'],
            [['nickname', 'avatar', 'gender'], 'safe']
        ];
    }
    
    /**
     * 登录
     * @return boolean
     */
    public function login()
    {
        if ($this->validate()) {
            if ($this->_user === null) {
                if(!$this->_user = User::findOne(['instagram_user_id' => $this->instagram_user_id])) {
                    $this->register();
                } else {
                    $this->update();
                }
            }
            if($this->_user && Yii::$app->user->login($this->_user, 0)) {
                return $this->_user->generateAccessToken();
            }
        }
        return false;
    }
    
    /**
     * 三方登录用户不存在，则自动创建用户账号
     */
    public function register()
    {
        $user = new User();
        $user->nickname = $this->nickname;
        $user->avatar = $this->avatar;
        $user->instagram_user_id = $this->instagram_user_id;
        $user->instagram_access_token = $this->instagram_access_token;
        $user->login_ip = ip2long(Yii::$app->request->getUserIP());
        $user->login_at = date('Y-m-d H:i:s');
        if($user->save()) {
            $this->_user = User::findOne(['instagram_user_id' => $this->instagram_user_id]);
        }
    }
    
    public function update()
    {
        if(null === $this->_user) {
            return false;
        }
        $this->_user->nickname = $this->nickname;
        $this->_user->avatar = $this->avatar;
        $this->_user->instagram_user_id = $this->instagram_user_id;
        $this->_user->instagram_access_token = $this->instagram_access_token;
        $this->_user->login_ip = ip2long(Yii::$app->request->getUserIP());
        $this->_user->login_at = date('Y-m-d H:i:s');
        $this->_user->save();
    }
    
    public static function getGender($gender)
    {
        if (is_numeric($gender) && !in_array($gender, array_keys(User::getGenders()))) {
            return User::GENDER_PRIVACY;
        }
        
        if($gender == '男' || $gender == 'M' || $gender == 'm') {
            return User::GENDER_MALE;
        } else {
            return User::GENDER_FEMALE;
        }
    }
    
    
}