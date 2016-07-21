<?php

namespace common\modules\user\api\controllers;

use Yii;
use common\modules\core\api\components\BaseController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\user\models\User;
use common\components\core\Tools;

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
        ];
        return $behaviors;
    }
    
    public function actionIndex()
    {
        echo 'abc';exit;
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