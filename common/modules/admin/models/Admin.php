<?php
namespace common\modules\admin\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Admin extends ActiveRecord implements IdentityInterface
{
    //用户状态
    const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_SUSPENDED = 2;
    
    //用户类型
    const USER_TYPE_SUPERADMIN = 1;     //超级管理员
    const USER_TYPE_NORMALADMIN = 2;    //普通管理员

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_admins}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username','required'],
            ['username','unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETE, self::STATUS_SUSPENDED]],
            ['mobile', 'match', 'pattern'=>'/^[1][0-9]{10}$/','message' => '手机号格式不正确'],
            ['email','email'],
            ['remark','safe'],
            ['password','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($adminId)
    {
        return static::findOne(['admin_id' => $adminId, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
        throw new NotSupportedException('"getAuthKey" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('"validateAuthKey" is not implemented.');
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
     * 获取所有用户状态
     * @return array
     */
    public static function getStatus()
    {
        return [
            self::STATUS_DELETE => '永久停用',
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
            self::USER_TYPE_SUPERADMIN => '超级管理员',
            self::USER_TYPE_NORMALADMIN => '普通管理员',
        ];
    }

}
