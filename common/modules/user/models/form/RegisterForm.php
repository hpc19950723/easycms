<?php

namespace common\modules\user\models\form;

use Yii;
use yii\base\Model;
use common\modules\user\models\SecurityCode;
use common\modules\user\models\User;

class RegisterForm extends Model
{
    public $code;
    
    public $mobile;
    
    public $nickname;
    
    public $password;
    
    public $password2;
    
    public $type;
    
    const SCENARIOS_REGISTER = 'register';
    
    const SCENARIOS_WEB_REGISTER = 'web_register';
    
    private $_user;


    public function rules() {
        return [
            [['mobile', 'code', 'password'], 'required'],
            ['nickname', 'required'],
            ['nickname', 'string', 'length' => [2, 12]],
            ['nickname', 'unique', 'targetClass' => 'common\modules\user\models\User', 'message' => '该昵称已经存在,请重新输入'],
            ['mobile', 'match', 'pattern'=>'/^[1][0-9]{10}$/','message' => '手机号格式不正确'],
            ['password', 'string', 'min' => 6, 'max' => 32],
            ['password2', 'compare', 'compareAttribute' => 'password', 'message' => '确认密码不一致'],
            ['mobile', 'unique', 'targetClass' => 'common\modules\user\models\User', 'message' => '您输入的手机号已经注册, 请直接登录', 'on' => [self::SCENARIOS_REGISTER, self::SCENARIOS_WEB_REGISTER]],
            ['code', 'validateCode'],
        ];
    }
    
    
    public function attributeLabels() {
        return [
            'nickname' => '昵称',
            'mobile' => '手机号',
            'code' => '验证码',
            'password' => '密码',
            'password2' => '确认密码',
        ];
    }
    
    
    public function validateCode()
    {
        if(!$this->hasErrors()) {
            $count = SecurityCode::find()->where('mobile=:mobile and type=:type and code=:code and expiration>=:time', [
                ':mobile' => $this->mobile,
                ':type' => SecurityCode::TYPE_REGISTER,
                ':code' => $this->code,
                ':time' => date('Y-m-d H:i:s')
            ])->count();
            
            if($count == 0) {
                $this->addError('code', '您输入的验证码不正确或验证码已过期');
            }
        }
    }
    

    public function scenarios() {
        $scenarios = [
            self::SCENARIOS_REGISTER => ['mobile', 'code', 'password'],
            self::SCENARIOS_WEB_REGISTER => ['mobile', 'nickname', 'code', 'password', 'password2'],
         ];
        return array_merge( parent:: scenarios(), $scenarios);
    }


    public function save()
    {
        if($this->validate()) {
            $this->_user = new User();
            $this->_user->mobile = $this->mobile;
            if($this->nickname !== null) {
                $this->_user->nickname = $this->nickname;
            }
            $this->_user->setPassword($this->password);
            $this->_user->access_token = Yii::$app->security->generateRandomString(32);
            $this->_user->save();

            //网站注册,自动登录
            if($this->getScenario() == self::SCENARIOS_WEB_REGISTER) {
                Yii::$app->user->login($this->_user, 0);
            }
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * 获取accessToken
     * @return boolean
     */
    public function getAccessToken()
    {
        if($this->_user) {
            return $this->_user->access_token;
        } else {
            return false;
        }
    }
}