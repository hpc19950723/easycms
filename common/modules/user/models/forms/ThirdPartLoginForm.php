<?php

namespace common\modules\user\models\forms;

use Yii;
use yii\base\Model;
use common\modules\user\models\User;
use common\modules\core\components\Tools;

class ThirdPartLoginForm extends Model
{
    public $avatar; //头像URl
    public $nickname;
    public $gender;
    public $thirdPartId;    //第三方登录唯一ID
    public $type;
    private $_user;
    
    const SCENARIOS_WEIBO_LOGIN = 'weibo_login';
    const SCENARIOS_WECHAT_LOGIN = 'wechat_login';
    const SCENARIOS_QQ_LOGIN = 'qq_login';
    
    public function rules()
    {
        return [
            [['thirdPartId', 'type'], 'required'],
            [['nickname', 'avatar', 'gender'], 'safe'],
            ['type', 'in', 'range' => static::getTypes()]
        ];
    }
    
    
    public function scenarios()
    {
        return [
            static::SCENARIOS_QQ_LOGIN => ['nickname', 'avatar', 'gender', 'thirdPartId', 'type'],
            static::SCENARIOS_WEIBO_LOGIN => ['nickname', 'avatar', 'gender', 'thirdPartId', 'type'],
            static::SCENARIOS_WECHAT_LOGIN => ['nickname', 'avatar', 'gender', 'thirdPartId', 'type'],
        ];
    }
    
    
    /**
     * 登录
     * @return boolean
     */
    public function login()
    {
        if ($this->validate()) {
            if(Yii::$app->user->login($this->getUser(), 0)) {
                return $this->_user->generateAccessToken();
            }
        }
        return false;
    }

    /**
     * Finds user by thirdPartId, type
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne([$this->type => $this->thirdPartId]);
            if($this->_user === null) {
                $this->_user = new User();
                if($avatar = $this->saveAvatar()) {
                    $this->_user->avatar = $avatar;
                }
                $this->_user->nickname = User::getUnique($this->nickname);
                $this->_user->gender = User::getGender($this->gender);
                $this->_user->user_type = User::USER_TYPE_NORMAL;
                $this->_user->allow_post = User::ALLOW_POST;
                $this->_user->status = User::STATUS_ACTIVE;
                $this->_user->{$this->type} = $this->thirdPartId;
                $this->_user->save();
            } elseif($this->_user->status != User::STATUS_ACTIVE) {
                return;
            }
        }

        return $this->_user;
    }
    
    
    public static function getTypes()
    {
        return [
            'wechat_oauth',
            'qq_oauth',
            'weibo_oauth'
        ];
    }
    
    
    public function saveAvatar()
    {
        if($this->avatar) {
            $file = file_get_contents($this->avatar);
            if (strlen($file) > 0) {
                $imageName = uniqid(Tools::getModuleParams('user', ['uploads', 'avatar', 'prefix'])) . '.jpg';
                $filePath = Tools::getModuleParams('user', ['uploads', 'avatar', 'dir']) . $imageName;
                file_put_contents($filePath, $file);
                return $imageName;
            } else {
                return;
            }
        } else {
            return;
        }
    }
}