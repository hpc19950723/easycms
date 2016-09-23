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
    
    public $thirdPartId;    //第三方登录唯一ID
    
    public $code; //验证码
    
    public $mobile;
    
    public $password;
    
    public $password2;
    
    private $_user;
    
    const SCENARIOS_WEIBO_LOGIN = 'weibo_login';
    
    const SCENARIOS_WECHAT_LOGIN = 'wechat_login';
    
    const SCENARIOS_QQ_LOGIN = 'qq_login';
    
    const SCENARIOS_WEIBO_BIND = 'weibo_bind';
    
    const SCENARIOS_WECHAT_BIND = 'wechat_bind';
    
    const SCENARIOS_QQ_BIND = 'qq_bind';
    
    public function rules()
    {
        return [
            [['thirdPartId', 'type'], 'required'],
            [['nickname', 'avatar', 'gender'], 'safe'],
            [['mobile', 'password', 'code'], 'required'],
            ['mobile', 'match', 'pattern'=>'/^[1][0-9]{10}$/','message' => '手机号格式不正确'],
            ['password', 'string', 'min' => 6, 'max' => 32],
            ['password2', 'compare', 'compareAttribute' => 'password', 'message' => '确认密码不一致'],
            ['code', 'exist', 'targetClass' => 'common\modules\user\models\SecurityCode', 'filter' => function($query) {
                $query->andWhere([
                    'mobile' => $this->mobile,
                    'type' => SecurityCode::TYPE_USER_BIND
                ])->andWhere([
                    '>=', 'expiration', date('Y-m-d H:i:s')
                ]);
            }, 'message' => '您输入的验证码不正确或验证码已过期'],
            ['mobile', 'validateMobile'],
        ];
    }
    
    /**
     * 验证手机号
     * @param string $attribute
     * @param array $params
     */
    public function validateMobile($attribute, $params)
    {
        $this->_user = User::findOne(['mobile' => $this->mobile]);
        
        if ($this->_user === null && $this->password === null) {
            throw new HttpException(200, '设置账号密码', 10210);
        }
    }
    
    
    public function scenarios()
    {
        return [
            static::SCENARIOS_QQ_LOGIN => ['thirdPartId'],
            static::SCENARIOS_WEIBO_LOGIN => ['thirdPartId'],
            static::SCENARIOS_WECHAT_LOGIN => ['thirdPartId'],
            static::SCENARIOS_WEIBO_BIND => ['avatar', 'nickname', 'gender', 'thirdPartId', 'code', 'mobile', 'password', 'password2'],
            static::SCENARIOS_WECHAT_BIND => ['avatar', 'nickname', 'gender', 'thirdPartId', 'code', 'mobile', 'password', 'password2'],
            static::SCENARIOS_QQ_BIND => ['avatar', 'nickname', 'gender', 'thirdPartId', 'code', 'mobile', 'password', 'password2']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
            'code' => '验证码'
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
                $this->_user = User::findOne([static::getThirdPartField($this->getScenario()) => $this->thirdPartId]);
            }
            if($this->_user && Yii::$app->user->login($this->_user, 0)) {
                return $this->_user->generateAccessToken();
            }
        }
        return false;
    }
    
    /**
     * 三方账号绑定
     * @return token|false
     */
    public function bind()
    {
        if ($this->validate()) {
            if ($this->_user === null) {
                $this->_user = new User();
                if($avatar = $this->saveAvatar()) {
                    $this->_user->avatar = $avatar;
                }
                $this->_user->mobile = $this->mobile;
                $this->_user->nickname = User::getUniqueNickname($this->nickname);
                $this->_user->gender = static::getGender($this->gender);
                $this->_user->user_type = User::USER_TYPE_NORMAL;
                $this->_user->setPassword($this->password);
                $this->_user->access_token = Yii::$app->security->generateRandomString(32);
                $this->_user->{static::getThirdPartField($this->getScenario())} = $this->thirdPartId;
            } else {
                $this->_user->nickname = Tools::getValue($this->_user->nickname, $this->nickname);
                $this->_user->avatar = Tools::getValue($this->_user->avatar, function() {
                    return $this->saveAvatar();
                });
                $this->_user->{static::getThirdPartField($this->getScenario())} = $this->thirdPartId;
                $this->_user->access_token = Yii::$app->security->generateRandomString(32);
            }
            if ($this->_user->save()) {
                Yii::$app->user->login($this->_user, 0);
                return $this->_user->access_token;
            } else {
                return false;
            }
        } else {
            return false;
        }
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

    
    public static function getThirdPartField($scenarios)
    {
        switch ($scenarios) {
            case static::SCENARIOS_WECHAT_LOGIN:
            case static::SCENARIOS_WECHAT_BIND:
                return 'wechat_oauth';
            case static::SCENARIOS_QQ_LOGIN:
            case static::SCENARIOS_QQ_BIND:
                return 'qq_oauth';
            case static::SCENARIOS_WEIBO_LOGIN:
            case static::SCENARIOS_WEIBO_BIND:
                return 'weibo_oauth';
        }
    }
    
    
    /**
     * 保存头像
     * @return string|null
     */
    public function saveAvatar()
    {
        if($this->avatar) {
            $client = new Client();
            $request = $client->request('GET', $this->avatar);
            $body = $request->getBody();
            if (strlen($body) > 0) {
                $imageName = Tools::getUploadDir('user') . '/' .uniqid('AVAT');
                $filePath = Yii::getAlias('@uploads' . $imageName);
                file_put_contents($filePath, $body);
                $imageSize = getimagesize($filePath);
                rename($filePath, $filePath . '_' . $imageSize[0] . 'x' . $imageSize[1] . '.jpg');
                $imageName = $imageName . '_' . $imageSize[0] . 'x' . $imageSize[1] . '.jpg';
                return $imageName;
            } else {
                return;
            }
        } else {
            return;
        }
    }
}