<?php

namespace common\modules\user\models\forms;

use Yii;
use yii\base\Model;
use common\modules\user\models\SecurityCode;
use common\modules\user\models\User;
use common\modules\core\components\Sms;

class SecurityCodeForm extends Model
{
    public $mobile;
    
    public $code;
    
    public $type;
    
    const SCENARIOS_REGISTER = 'register';
    
    const SCENARIOS_RESET_PASSWORD = 'reset_password';
    
    const SCENARIOS_LOGIN = 'login';


    public function rules()
    {
        return [
            [['mobile', 'code', 'type'], 'required'],
            ['type', 'in', 'range' => [SecurityCode::TYPE_REGISTER, SecurityCode::TYPE_RESET_PASSWORD, SecurityCode::TYPE_LOGIN]],
            ['mobile', 'match', 'pattern'=>'/^[1][0-9]{10}$/','message' => '手机号格式不正确'],
            ['mobile', 'unique', 'targetClass' => 'common\modules\user\models\User', 'message' => '您输入的手机号已被注册, 请更换手机号', 'on' => [self::SCENARIOS_REGISTER]],
            ['mobile', 'existMobile', 'on' => [self::SCENARIOS_RESET_PASSWORD, self::SCENARIOS_LOGIN]], //登录或重置密码需验证
            ['mobile', 'validateMobile'],
        ];
    }
    
    
    /**
     * 验证手机号是否存在
     */
    public function existMobile()
    {
        if(!$this->hasErrors()) {
            $count = User::find()->where('mobile=:mobile and status>:status', [':mobile' => $this->mobile, ':status' => User::STATUS_DELETE])->count();
            if($count == 0) {
                $this->addError('mobile', '您的输入到手机号不存在');
            }
        }
    }
    
    
    /**
     * 验证码是否请求频繁
     */
    public function validateMobile()
    {
        if(!$this->hasErrors()) {
            $count = SecurityCode::find()->where('mobile=:mobile and created_at > :time', [':mobile' => $this->mobile, ':time' => date('Y-m-d H:i:s', time()-60)])->count();
            if($count > 0) {
                $this->addError('mobile', '验证码请求过于频繁,请稍后再试');
            }
        }
    }

    
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'code' => '验证码'
        ];
    }
    
    
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_REGISTER => ['mobile', 'code', 'type'],
            self::SCENARIOS_RESET_PASSWORD => ['mobile', 'code', 'type'],
            self::SCENARIOS_LOGIN => ['mobile', 'code', 'type'],
         ];
        return array_merge( parent::scenarios(), $scenarios);
    }
    
    
    public function getScenarios()
    {
        return [
            SecurityCode::TYPE_REGISTER => self::SCENARIOS_REGISTER,
            SecurityCode::TYPE_RESET_PASSWORD => self::SCENARIOS_RESET_PASSWORD,
            SecurityCode::TYPE_LOGIN => self::SCENARIOS_LOGIN
        ];
    }
    
    
    public function save()
    {
        if($this->validate()) {
            $sms = Yii::$app->sms->set([
                'mobile' => $this->mobile,
                'smParams' => [
                    $this->code
                ],
                'type' => $this->getScenario()
            ]);
            
            //保存验证码记录
            $model = new SecurityCode();
            $model->mobile = $this->mobile;
            $model->code = $this->code;
            $model->type = $this->type;
            $model->content = $sms->getMessage();
            
            if($model->save()) {
                //发送短信
                return Yii::$app->sms->send();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}