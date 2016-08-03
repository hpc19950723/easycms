<?php
namespace common\modules\user\models\form;

use Yii;
use yii\base\Model;
use common\modules\user\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $mobile;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '您输入的手机号或者密码不正确,请重新输入');
            }
        }
    }

    /**
     * Logs in a user using the provided mobile and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[mobile]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByMobile($this->mobile);
        }

        return $this->_user;
    }
    
    /**
     * 获取access token
     * @return boolean
     */
    public function getAccessToken()
    {
        if ($this->validate()) {
            return $this->getUser()->generateAccessToken();
        } else {
            return false;
        }
    }
    
    
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
        ];
    }
}
