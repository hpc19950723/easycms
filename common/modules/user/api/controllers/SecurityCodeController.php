<?php

namespace common\modules\user\api\controllers;

use Yii;
use common\modules\core\components\Tools;
use common\modules\user\models\form\SecurityCodeForm;
use common\modules\user\models\SecurityCode;

class SecurityCodeController extends \common\modules\core\api\components\BaseController
{
    /**
     * 发送注册验证码
     * @return array
     */
    public function actionRegister()
    {
        return $this->send(SecurityCode::TYPE_REGISTER);
    }
    
    
    /**
     * 发送忘记密码验证码
     * @return array
     */
    public function actionResetPassword()
    {
        return $this->sendCaptcha(SecurityCode::TYPE_RESET_PASSWORD);
    }
    
    
    public function send($type)
    {
        $code = Tools::getRandomNumber(6, 'number');
        
        $data = [
            'mobile' => Yii::$app->request->get('mobile'),
            'code' => $code,
            'type' => $type
        ];
        $model = new SecurityCodeForm();
        $model->setScenario(SecurityCodeForm::getScenarios()[$type]);
        if($model->load($data, '') && $model->save()) {
            return self::formatSuccessResult();
        } else {
            return self::formatResult(10201, Yii::t('error', 'Send captcha failed'), $model->errors);
        }
    }
}