<?php

namespace common\modules\admin\models\forms;

use Yii;
use common\modules\admin\models\Admin;
use yii\base\Model;

class UpdatePasswordForm extends Model
{
    
    public $old_password;
    
    public $password;
    
    public $password2;
    
    private $_admin;
    
    public function rules()
    {
        return [
            [['old_password', 'password', 'password2'], 'required'],
            [['old_password', 'password', 'password2'], 'string', 'length' => [6, 32]],
            ['password2', 'compare', 'compareAttribute' => 'password', 'message' => '确认密码不一致'],
            ['old_password', 'validatePassword'],
        ];
    }
    
    /**
     * 验证密码是否正确
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = $this->findModel();
            if($model === null || !$model->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }
    
    public function attributeLabels()
    {
        return [
            'old_password' => '原始密码',
            'password' => '新密码',
        ];
    }
    
    public function findModel()
    {
        if($this->_admin === null) {
            $this->_admin = Admin::findOne(Yii::$app->user->getId());
        }
        return $this->_admin;
    }
    
    public function save()
    {
        $model = $this->findModel();
        $model->setPassword($this->password);
        return $model->save();
    }
}
