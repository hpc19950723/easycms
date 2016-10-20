<?php

namespace common\modules\user\models\forms;

use Yii;
use yii\base\Model;
use common\modules\user\models\User;

class UpdatePasswordForm extends Model
{
    
    public $old_password;
    
    public $password;
    
    public $password2;
    
    private $_user;
    
    const SCENARIOS_API_UPDATE_PASSWORD = 'api_change_password';
    
    
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
            $model = $this->getUser();
            if($model === null || !$model->validatePassword($this->old_password)) {
                $this->addError($attribute, '原始密码不正确');
            }
        }
    }
    
    
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_API_UPDATE_PASSWORD => ['old_password', 'password', 'password2'],
         ];
        return array_merge( parent::scenarios(), $scenarios);
    }
    
    
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(Yii::$app->user->getId());
        }

        return $this->_user;
    }
    
    
    public function save()
    {
        if($this->validate()) {
            $model = $this->getUser();
            $model->setPassword($this->password);
            return $model->save();
        } else {
            return false;
        }
    }
    
    
    public function attributeLabels() {
        return [
            'old_password' => '原始密码',
            'password' => '密码',
            'password2' => '确认密码'
        ];
    }
}
