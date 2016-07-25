<?php

namespace common\modules\user\api\controllers;

use Yii;
use common\modules\core\api\components\BaseController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\user\models\User;
use common\components\core\Tools;
use common\modules\user\models\form\RegisterForm;

class IndexController extends BaseController
{
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                QueryParamAuth::className(),
            ],
            'optional' => ['create']
        ];
        return $behaviors;
    }
    
    
    /**
     * 用户注册
     */
    public function actionCreate()
    {
        $model = new RegisterForm();
        
        $model->setScenario(RegisterForm::SCENARIOS_REGISTER);
        if($model->load(Yii::$app->request->post(), '') && $model->save()) {
            $data = [
                'accessToken' => $model->getAccessToken()
            ];
            return self::formatSuccessResult($data);
        } else {
           return self::formatResult(10202, Yii::t('user', 'Register fail'), $model->errors);
        }
    }
    
    
    /**
     * 获取用户基本信息
     * @return array
     */
    public function actionView($id)
    {
        $model = User::findOne($id);
        return self::formatSuccessResult($model);
    }
}