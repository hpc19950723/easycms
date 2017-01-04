<?php
namespace common\modules\feedback\api\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\feedback\models\forms\FeedbackForm;
use common\modules\core\components\Tools;

class IndexController extends \common\modules\core\api\components\BaseController
{
    /**
     * @inheritdoc
     */
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
     * 创建反馈
     */
    public function actionCreate()
    {
        $model = new FeedbackForm();
        
        if($model->load(Tools::getPost(['user_id' => Yii::$app->user->getId()]), '') && $model->save()) {
            return self::formatSuccessResult();
        } else {
            return self::formatResult(10206, Tools::getFirstError($model->errors, Yii::t('feedback', 'Create feedback failed')));
        }
    }
}