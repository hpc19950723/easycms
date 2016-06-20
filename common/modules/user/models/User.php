<?php
namespace common\modules\user\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use common\modules\core\models\CommonActiveRecord;
use yii\web\IdentityInterface;
use yii\db\Expression;
use common\components\Tools;
use common\components\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $mobile
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends CommonActiveRecord implements IdentityInterface
{
    //用户状态
    const STATUS_FORBIDDEN = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_SUSPENDED = 2;

    //用户类型
    const USER_TYPE_NORMAL = 1;
    const USER_TYPE_GENIUS_RETAIL_INVESTOR = 2;
    const USER_TYPE_AFP = 3;

    //用户性别
    const GENDER_PRIVACY = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    //是否允许发帖
    const FORBID_POST = 0;
    const ALLOW_POST = 1;
    
    //新预约
    const HAS_NOT_NEW_APPOINTMENT = 0;
    const HAS_NEW_APPOINTMENT = 1;

    const SCENARIOS_FRONTEND_UPDATE = 'frontend_update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_member}}';
    }

    /**
     * @inheritdoc
     */
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


    public function beforeSave($insert) {
        if(false === $insert) {
            if($this->avatar instanceof \yii\web\UploadedFile) {
                $imageName = uniqid(Yii::$app->params['upload']['avatar']['prefix']) . '.' . $this->avatar->extension;
                $this->avatar->saveAs(Yii::$app->params['upload']['avatar']['dir'] . $imageName);
                $this->avatar = $imageName;
            } else {
                unset($this->avatar);
            }
        }
        return parent::beforeSave($insert);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['gender', 'default', 'value' => self::GENDER_PRIVACY],
            ['user_type', 'default', 'value' => self::USER_TYPE_NORMAL],
            ['grade', 'default', 'value' => 0],
            ['status', 'in', 'range' => [self::STATUS_FORBIDDEN, self::STATUS_ACTIVE, self::STATUS_SUSPENDED]],
            ['user_type', 'in', 'range' => [self::USER_TYPE_NORMAL, self::USER_TYPE_GENIUS_RETAIL_INVESTOR, self::USER_TYPE_AFP]],
            ['gender', 'in', 'range' => [self::GENDER_PRIVACY, self::GENDER_MALE, self::GENDER_FEMALE]],
            ['allow_post', 'in', 'range' => [self::FORBID_POST, self::ALLOW_POST]],
            ['nickname', 'unique'],
            ['email', 'email'],
            [['real_name','qq','wechat'], 'string', 'length' => [2, 255]],
            ['grade', 'integer'],
            ['bio','safe'],
            [['height', 'weight', 'appointment_fee'], 'double'],
            [['id_no'], 'match', 'pattern'=>'/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/', 'message' => '身份证号码格式不正确'],
            [['opened_at'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            ['avatar', 'image', 'extensions' => 'gif, jpg, png', 'mimeTypes' => 'image/jpeg, image/png, image/gif', 'checkExtensionByMimeType' => false],
        ];
    }

    public function fields() {
        return [
            'user_id',
            'mobile',
            'mobile2',
            'nickname',
            'avatar' => function($model) {
                return Tools::getFileUrl($model->avatar, 'images/avatar');
            },
            'bio',
            'real_name',
            'gender',
            'email',
            'qq',
            'wechat',
            'id_no',
            'user_type',
            'allow_post',
            'appointment_fee',
            'grade',
            'fans_qty',
            'status',
            'new_appointment'
        ];
    }
    
    
    public function extraFields()
    {
        return [
            'certification_status' => function($model) {
                if(!empty($model->certification)) {
                    return $model->certification->status;
                } else {
                    return Certification::STATUS_NOT_APPROVED;
                }
            },
            'vprice' => function($model) {
                if(!empty($model->vprice)) {
                    return $model->vprice->vprice - $model->vprice->hold;
                } else {
                    return 0;
                }
            },
            'viewVirtualQuotation' => function($model) {
                $visibility = false;
                if($model->virtualQuotation !== null) {
                    if($this->user_id == Yii::$app->user->getId()) {
                        $visibility = true;
                    } else {
                        switch ($model->virtualQuotation->visibility) {
                            case VirtualQuotation::VISIBILITY_ALL:
                                $visibility = true;
                                break;
                            case VirtualQuotation::VISIBILITY_FANS:
                                $count = GroupFollow::find()->where(['group_user_id' => $this->user_id, 'user_id' => Yii::$app->user->getId()])
                                    ->count();
                                if($count > 0) {
                                    $visibility = true;
                                }
                                break;
                        }
                    }
                }
                return $visibility;
            }
        ];
    }


    public function attributeLabels() {
        return [
            'gender' => '性别',
            'email' => '邮箱',
            'nickname' => '昵称',
            'real_name' => '真实姓名',
            'bio' => '个人简介',
            'id_no' => '身份证号',
            'qq' => 'QQ号',
            'wechat' => '微信号',
            'avatar' => '头像',
            'appointment_fee' => '预约费用',
        ];
    }


    public function scenarios() {
        $scenarios = [
            self::SCENARIOS_FRONTEND_UPDATE => ['gender', 'nickname', 'bio', 'real_name', 'qq', 'wechat', 'id_no', 'email', 'avatar', 'appointment_fee', 'mobile2'],
        ];
        return array_merge( parent:: scenarios(), $scenarios);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->joinWith(['vprice'])->where([
            'access_token' => $token
        ])->one();
    }

    /**
     * Finds user by mobile
     *
     * @param string $mobile
     * @return static|null
     */
    public static function findByMobile($mobile)
    {
        return static::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 获取所有用户状态
     * @return array
     */
    public static function getStatus()
    {
        return [
            self::STATUS_FORBIDDEN => '永久停用',
            self::STATUS_ACTIVE => '已启用',
            self::STATUS_SUSPENDED => '暂时停用',
        ];
    }

    /**
     * 获取所有用户类型
     * @return array
     */
    public static function getUserType()
    {
        return [
            self::USER_TYPE_NORMAL => '普通用户',
            self::USER_TYPE_GENIUS_RETAIL_INVESTOR => '牛散',
            self::USER_TYPE_AFP => '理财师',
        ];
    }

    /*
     * 获取性别
     * @return array
     */
    public static function getGenders()
    {
        return [
            self::GENDER_PRIVACY => '保密',
            self::GENDER_MALE => '男',
            self::GENDER_FEMALE => '女',
        ];
    }

    /**
     * 获取是否允许发帖选项
     * @return array
     */
    public static function getAllowPostOptions()
    {
        return [
            self::FORBID_POST => '禁止发帖',
            self::ALLOW_POST => '允许发帖'
        ];
    }


    public function generateAccessToken() {
        $accessToken = Yii::$app->security->generateRandomString(32);
        $this->access_token = $accessToken;
        if($this->save(false)) {
            return $accessToken;
        } else {
            return false;
        }
    }
    
    
    public function getCertification()
    {
        return $this->hasOne(Certification::className(), ['user_id' => 'user_id']);
    }
    
    
    public function getVprice()
    {
        return $this->hasOne(Vprice::className(), ['user_id' => 'user_id']);
    }
    
    
    public function getVirtualQuotation()
    {
        return $this->hasOne(VirtualQuotation::className(), ['user_id' => 'user_id']);
    }
    
    
    public function getVirtualQuotationFund()
    {
        return $this->hasMany(VirtualQuotationFund::className(), ['user_id' => 'user_id']);
    }
    
    
    public function getGroup()
    {
        return $this->hasMany(Group::className(), ['user_id' => 'user_id'])
                ->orderBy(['fans_qty' => SORT_DESC])
                ->limit(8);
    }

    
    public static function getUnique($nickName, $nickNames = null)
    {
        if($nickNames === null) {
            $userModels = static::find()
                ->where('nickname LIKE :query')
                ->addParams([':query'=>$nickName.'%'])
                ->all();
            $nickNames = ArrayHelper::getColumn($userModels, 'nickname');
        }

        $result = in_array($nickName,$nickNames);
        if($result) {
            $nickName = User::createUniqueNick($nickName);
            return self::getUnique($nickName, $nickNames);
        }

        return $nickName;
    }

    public static function getGender($gender = '')
    {
        if($gender == '男' || $gender == 'M' || $gender == 'm')
            return 1;
        else
            return 2;
    }


    public static function getUniqueness($oauthType,$openId)
    {
        $model = static::find()
            ->where($oauthType.'= :openid', [':openid' => $openId])
            ->one();
        if($model === null){
            return false;
        }else{
            return $model->generateAccessToken();
        }

    }


    /*
     * 生成唯一昵称
     * @return array
     */
    public static function createUniqueNick($nickName)
    {
        $str = Tools::getRandomNumber(4, 'number');
        return $nickName . $str;
    }


    /**
     * 更新粉丝数
     * @param int $userId
     */
    public static function updateFansCount($userId)
    {
        $userFansCount = GroupFollow::find()->where(['group_user_id' => $userId])
            ->groupBy(['group_user_id', 'user_id'])
            ->count();

        User::updateAll(['fans_qty' => $userFansCount], ['user_id' => $userId]);
    }
}
