<?php

namespace common\modules\user\api\controllers;

use Yii;
use common\modules\core\components\Tools;
use common\modules\user\models\forms\SecurityCodeForm;

class SecurityCodeController extends \common\modules\core\api\components\BaseController
{
    public function createAction($id)
    {
        if ($id === '') {
            $id = $this->defaultAction;
        }
        
        $actionMap = [
            $id => [
                'class' => 'common\modules\user\api\controllers\actions\SecurityCodeAction'
            ]
        ];
        return Yii::createObject($actionMap[$id], [$id, $this]);
    }
    
    
    /**
     * 编辑配置信息
     * @param string $type 短信类型
     * @return array
     */
    public function send($type)
    {
        $code = Tools::getRandomNumber(6, 'number');
        $data = [
            'mobile' => Yii::$app->request->get('mobile'),
            'code' => $code,
            'type' => $type
        ];
        
        $model = new SecurityCodeForm();
        if (!in_array($type, array_keys($model->scenarios()))) {
            throw new \yii\web\NotFoundHttpException('请求接口不存在');
        }
        $model->setScenario($type);
        if($model->load($data, '') && $model->save()) {
            return self::formatSuccessResult();
        } else {
            return self::formatResult(10201, Tools::getFirstError($model->errors));
        }
    }
}