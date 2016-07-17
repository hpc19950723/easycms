<?php

namespace common\modules\admin\models\forms;

use Yii;
use yii\base\Model;
use common\modules\admin\models\Admin;
use yii\web\NotFoundHttpException;

class AccountForm extends Model
{
    public $username;
    
    public $status;
    
    public $mobile;
    
    public $remark;
    
    public $email;
    
    public $password;
    
    public $role;
    
    private $_oldRole;
    
    private $_adminModel;


    public function rules()
    {
        return [
            [['username', 'mobile', 'remark', 'email'], 'required'],
            //如果是创建账号,密码为必填,when,whenClent分别代表后端,前端的验证
            ['password', 'required', 'when' => function(){
                return $this->getIsNewRecord();
            }, 'whenClient' => "function () {
                return " . $this->getIsNewRecord() . ";
            }"],
            ['username', 'unique', 'when' => function(){
                return $this->getIsNewRecord() || $this->_adminModel->username != $this->username;
            }, 'targetClass' => 'common\modules\admin\models\Admin', 'message' => '当前用户名已经存在'],
            ['status', 'default', 'value' => Admin::STATUS_ACTIVE],
            ['status', 'in', 'range' => [Admin::STATUS_ACTIVE, Admin::STATUS_DELETE, Admin::STATUS_SUSPENDED]],
            ['mobile', 'match', 'pattern'=>'/^[1][0-9]{10}$/','message' => '手机号格式不正确'],
            ['email','email'],
            ['remark','safe'],
            ['remark','safe'],
            ['role', 'in', 'range' => self::getRoles()],
        ];
    }
    
    
    /**
     * 获取是否为创建订单
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_adminModel === null;
    }
    
    
    public function save()
    {
        if($this->validate()) {
            if($this->getIsNewRecord()) {
                $this->_adminModel = new Admin();
            }
            if(!empty($this->password)) {
                $this->_adminModel->setPassword($this->password);
            }
            $this->_adminModel->username = $this->username;
            $this->_adminModel->mobile = $this->mobile;
            $this->_adminModel->email = $this->email;
            $this->_adminModel->remark = $this->remark;
            $this->_adminModel->status = $this->status;
            $this->_adminModel->save();
            $this->authAssign($this->role, $this->_adminModel->getId());
            return true;
        } else {
            return false;
        }
    }
    
    
    public function getAccount($id)
    {
        if($this->getIsNewRecord()) {
            if (($this->_adminModel = Admin::findOne($id)) !== null) {
                $this->username = $this->_adminModel->username;
                $this->mobile = $this->_adminModel->mobile;
                $this->email = $this->_adminModel->email;
                $this->remark = $this->_adminModel->remark;
                $this->status = $this->_adminModel->status;
                $this->_oldRole = $this->role = $this->getRole($this->_adminModel->getId());
                return $this;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            return $this;
        }
    }
    
    
    public static function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        return array_keys($roles);
    }
    
    
    public function authAssign($roleName, $userId)
    {
        if($this->_oldRole !== $roleName) {
            $auth = Yii::$app->authManager;
            if($this->_oldRole) {
                $role = $auth->getRole($this->_oldRole);
                $auth->revoke($role, $userId);
            }
            $role = $auth->getRole($roleName);
            $auth->assign($role, $userId);
        }
    }
    
    
    public function getRole($userId)
    {
        $auth = Yii::$app->authManager;
        $assignments = array_keys($auth->getAssignments($userId));
        if(0 < count($assignments)) {
            return $assignments[0];
        } else {
            return false;
        }
    }
}
