<?php
namespace common\modules\instagram\api\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\instagram\models\forms\CollectForm;
use yii\data\ActiveDataProvider;
use common\modules\instagram\models\Collect;
use common\modules\core\components\Tools;

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
        $userId = Yii::$app->user->getId();
        Tools::addQueryParams(['expand' => 'instagramUser']);
        $dataProvider = new ActiveDataProvider([
            'query' => Collect::find()->where(['user_id' => $userId])->orderBy('created_at desc')
        ]);
        
        return self::formatSuccessResult($dataProvider);
    }
    
    /**
     * 添加收藏
     */
    public function actionCreate()
    {
        $model = new CollectForm();
        
        if($model->load(Tools::getPost(['user_id' => Yii::$app->user->getId()]), '') && $model->save()) {
            return self::formatSuccessResult();
        } else {
            return self::formatResult(10301, Tools::getFirstError($model->errors));
        }
    }
    
    /**
     * 删除收藏
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return self::formatSuccessResult();
    }
    
    protected function findModel($id)
    {
        $model = Collect::findOne($id);
        if($model === null) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        
        return $model;
    }
}