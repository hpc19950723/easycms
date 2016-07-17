<?php

namespace common\modules\user\api\controllers;

use Yii;
use common\modules\user\api\components\BaseController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\user\models\User;
use common\components\core\Tools;

class UserController extends BaseController
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
        ];
        return $behaviors;
    }
    
    
    /**
     * 获取用户基本信息
     * @return array
     */
    public function actionView()
    {
        Tools::addQueryParams(['expand' => 'certification_status, vprice, new_appointment']);
        $model = User::findOne(Yii::$app->user->getId());
        return self::formatSuccessResult($model);
    }
}