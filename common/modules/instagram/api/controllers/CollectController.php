<?php
namespace common\modules\instagram\api\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;

class CollectController extends \common\modules\core\api\components\BaseController
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
     * 收藏列表
     */
    public function actionIndex()
    {
        
    }
    
    /**
     * 添加收藏
     */
    public function actionCreate()
    {
        $instagramUserId = Yii::$app->request->get('instagram_user_id');
    }
    
    /**
     * 删除收藏
     */
    public function actionDelete()
    {
        
    }
}