<?php

namespace common\modules\user\models\forms;

use Yii;
use yii\base\Model;
use common\modules\user\models\User;
use common\modules\core\components\ImageUploader;
use common\modules\core\components\Tools;

class UserForm extends Model
{
    public $mobile;
    
    public $nickname;
    
    public $password;
    
    public $real_name;
    
    public $gender = User::GENDER_PRIVACY;
    
    public $email;
    
    public $user_type = 1;
    
    public $qq;
    
    public $wechat;
    
    public $id_no;
            
    public $bio;
    
    public $avatar;
    
    public $status = User::STATUS_ACTIVE;
    
    private $_user;
    
    const SCENARIOS_BACKEND_CREATE = 'backend_create';
    
    const SCENARIOS_BACKEND_UPDATE = 'backend_update';
    
    const SCENARIOS_API_UPDATE = 'api_update';


    public function rules()
    {
        return [
            [['nickname', 'mobile'], 'required', 'on' => [self::SCENARIOS_BACKEND_CREATE, self::SCENARIOS_BACKEND_UPDATE]],
            ['password', 'required', 'on' => [self::SCENARIOS_BACKEND_CREATE]],
            ['gender', 'in', 'range' => [User::GENDER_PRIVACY, User::GENDER_MALE, User::GENDER_FEMALE]],
            [['nickname'], 'string', 'length' => [2, 20]],
            ['nickname', 'unique', 'targetClass' => '\common\modules\user\models\User', 'targetAttribute' => 'nickname', 'when' => function(){
                return $this->isNewRecord || $this->_user->nickname != $this->nickname;
            }],
            ['mobile', 'match', 'pattern'=>'/^[1][0-9]{10}$/','message' => '手机号格式不正确'],
            ['mobile', 'unique', 'targetClass' => '\common\modules\user\models\User', 'message' => '您输入的手机号已经注册, 请更换手机号', 'when' => function(){
                return $this->isNewRecord || $this->_user->mobile != $this->mobile;
            }],
            ['email', 'string', 'max' => 255],
            ['email', 'match', 'pattern' => '/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/'],
            [['password'], 'string', 'length' => [6, 32]],
            [['real_name', 'qq', 'wechat'], 'string', 'length' => [2, 255]],
            ['status', 'in', 'range' => array_keys(User::getStatus())],
            ['user_type', 'safe'],
            ['bio', 'string', 'length' => [0, 200]],
            [['id_no'], 'match', 'pattern'=>'/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/', 'message' => '身份证号码格式不正确'],
            ['avatar', 'image', 'extensions' => 'jpg, png', 'maxSize' => 2097152, 'mimeTypes' => 'image/jpeg, image/png', 'checkExtensionByMimeType' => false, 'tooBig' => '文件"{file}"太大, 它的大小不能超过2.00 MB'],
        ];
    }
    
    
    public function scenarios() {
        $scenarios = [
            self::SCENARIOS_BACKEND_CREATE => ['nickname', 'mobile', 'gender', 'user_type', 'status', 'email', 'password', 'real_name', 'bio', 'id_no', 'avatar', 'qq', 'wechat'],
            self::SCENARIOS_BACKEND_UPDATE => ['nickname', 'mobile', 'gender', 'user_type', 'status', 'email', 'password', 'real_name', 'bio', 'id_no', 'avatar', 'qq', 'wechat'],
            self::SCENARIOS_API_UPDATE => ['nickname', 'gender', 'email', 'real_name', 'bio', 'id_no', 'avatar', 'qq', 'wechat'],
         ];
        return array_merge( parent:: scenarios(), $scenarios);
    }
    
    
    /**
     * 用户数据
     * @param type $userId
     * @return type
     */
    public function initUser($userId)
    {
        $this->_user = User::findOne(['user_id' => $userId]);

        if($this->_user !== null) {
            $this->nickname = $this->_user->nickname;
            $this->mobile = $this->_user->mobile;
            $this->real_name = $this->_user->real_name;
            $this->gender = $this->_user->gender;
            $this->email = $this->_user->email;
            $this->qq = $this->_user->qq;
            $this->wechat = $this->_user->wechat;
            $this->id_no = $this->_user->id_no;
            $this->bio = $this->_user->bio;
            $this->status = $this->_user->status;
            $this->avatar = $this->_user->avatar;
        }
        
        return $this->_user;
    }
    
    
    /**
     * 获取是否为新记录
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_user === null;
    }
    
    
    public function attributeLabels()
    {
        return [
            'mobile'            => '手机号',
            'password'          => '密码',
            'nickname'          => '昵称',
            'real_name'         => '真实姓名',
            'gender'            => '性别',
            'email'             => '邮箱',
            'qq'                => 'QQ',
            'wechat'            => '微信',
            'id_no'             => '身份证号',
            'bio'               => '个人简介',
            'avatar'            => '头像',
            'user_type'         => '用户类型',
            'status'            => '状态'
        ];
    }
    
    
    /**
     * 保存数据
     * @return boolean
     */
    public function save()
    {
        if($this->validate()) {
            if($this->isNewRecord) {
                $this->_user = new User();
            }
            
            $fileUploader = new ImageUploader([
                'uploadedFile' => $this->avatar,
                'uploadedFileDir' => Tools::getUploadDir('user'),
                'uploadedFilePrefix' => 'AVAT',
                'oldFilePath' => $this->_user->avatar?:null
            ]);
            $imageName = $fileUploader->save();
            if($imageName) {
                $this->_user->avatar = Tools::getUploadDir('user') . '/' . $imageName;
            }
            
            $this->_user->mobile = $this->mobile;
            if($this->password !== '' || $this->password !== null) {
                $this->_user->setPassword($this->password);
            }
            $this->_user->nickname = $this->nickname;
            $this->_user->real_name = $this->real_name;
            $this->_user->gender = $this->gender;
            $this->_user->email = $this->email;
            $this->_user->qq = $this->qq;
            $this->_user->wechat = $this->wechat;
            $this->_user->id_no = $this->id_no;
            $this->_user->bio = $this->bio;
            $this->_user->status = $this->status;

            if($this->_user->save()) {
                return true;
            } else {
                $this->addErrors($this->_user->getErrors());
                return false;
            }
        } else {
            return false;
        }
    }
}