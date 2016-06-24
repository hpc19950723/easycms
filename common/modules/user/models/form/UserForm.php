<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

class UserForm extends Model
{
    public $user_id;
    public $nickname;
    public $real_name;
    public $gender;
    public $email;
    public $qq;
    public $wechat;
    public $id_no;
    public $appointment_fee;
    public $grade;
    public $bio;
    public $avatar;
    private $_user;


    public function init()
    {
        if($this->user_id !== null) {
            $this->initUser();
        }
    }
    
    
    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['gender', 'default', 'value' => User::GENDER_PRIVACY],
            ['gender', 'in', 'range' => [User::GENDER_PRIVACY, User::GENDER_MALE, User::GENDER_FEMALE]],
            ['nickname', 'unique', 'targetClass' => '\common\models\User', 'targetAttribute' => 'nickname', 'when' => function(){
                return $this->_user->nickname != $this->nickname;
            }],
            ['email', 'email'],
            [['real_name','qq','wechat'], 'string', 'length' => [2, 255]],
            ['bio','safe'],
            [['appointment_fee'], 'double'],
            [['id_no'], 'match', 'pattern'=>'/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/', 'message' => '身份证号码格式不正确'],
            ['avatar', 'image', 'extensions' => 'jpg, png', 'maxSize' => 2097152, 'mimeTypes' => 'image/jpeg, image/png', 'checkExtensionByMimeType' => false, 'tooBig' => '文件"{file}"太大, 它的大小不能超过2.00 MB'],
        ];
    }
    
    
    public function initUser()
    {
        $model = User::findOne(['user_id' => $this->user_id, 'status' => User::STATUS_ACTIVE]);

        if($model !== null) {
            $this->nickname = $model->nickname;
            $this->real_name = $model->real_name;
            $this->gender = $model->gender;
            $this->email = $model->email;
            $this->qq = $model->qq;
            $this->wechat = $model->wechat;
            $this->id_no = $model->id_no;
            $this->appointment_fee = $model->appointment_fee;
            $this->grade = $model->grade;
            $this->bio = $model->bio;
            $this->avatar = $model->avatar;
            $this->_user = $model;
        }
    }
    
    
    public function attributeLabels()
    {
        return [
            'nickname'          => '昵称',
            'real_name'         => '姓名',
            'gender'            => '性别',
            'email'             => '邮箱',
            'qq'                => 'QQ',
            'wechat'            => '微信',
            'id_no'             => '身份证号',
            'appointment_fee'   => '预约费用',
            'grade'             => '等级',
            'bio'               => '个人简介'
        ];
    }
    
    
    public function save()
    {
        if($this->validate()) {
            if($this->_user === null) {
                return false;
            } else {
                if($this->avatar instanceof \yii\web\UploadedFile) {
                    $this->_user->avatar = $this->avatar;
                }
        
                $this->_user->nickname = $this->nickname;
                $this->_user->real_name = $this->real_name;
                $this->_user->gender = $this->gender;
                $this->_user->email = $this->email;
                $this->_user->qq = $this->qq;
                $this->_user->wechat = $this->wechat;
                $this->_user->id_no = $this->id_no;
                $this->_user->appointment_fee = $this->appointment_fee;
                $this->_user->bio = $this->bio;
                $this->_user->save();
                $this->avatar = $this->_user->avatar;
                return true;
            }
        } else {
            return false;
        }
    }
}