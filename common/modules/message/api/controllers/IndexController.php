<?php
namespace common\modules\message\api\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\message\models\Message;
use common\modules\core\components\Tools;
use yii\data\ActiveDataProvider;
use common\modules\message\models\MessageAction;

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
     * 获取消息列表
     * @return array
     */
    public function actionIndex()
    {
        $type = Yii::$app->request->get('type', Message::TYPE_SYSTEM);
        
        Tools::addQueryParams(['expand' => 'isRead']);
        $dataProvider = new ActiveDataProvider([
            'query' => Message::find()->andWhere(['>=', 'created_at', Yii::$app->user->identity->created_at])
                ->andWhere('receiver_id = 0 or receiver_id = :receiverId', [':receiverId' => Yii::$app->user->getId()])
                ->andWhere(['type' => $type])
                ->orderBy(['created_at' => SORT_DESC])
        ]);
        
        return static::formatSuccessResult($dataProvider);
    }
    
    
    public function actionView($id)
    {
        $userId = Yii::$app->user->getId();
        $model = Message::find()
                ->where(['>=', 'created_at', Yii::$app->user->identity->created_at])
                ->andWhere(['message_id' => $id])
                ->andWhere('receiver_id = 0 or receiver_id = :receiverId', [':receiverId' => $userId])
                ->one();
        
        if ($model !== null && !$model->messageAction) {
            $messageActionModel = new MessageAction;
            $messageActionModel->message_id = $id;
            $messageActionModel->user_id = $userId;
            $messageActionModel->is_read = Message::VALUE_YES;
            $messageActionModel->save();
        }
        
        return static::formatSuccessResult($model);
    }
    
    
    /**
     * 获取各消息类型最新消息,及未读数量
     * @return array
     */
    public function actionGroup()
    {
        Tools::addQueryParams(['expand' => 'unreadCount']);
        $query = Message::find()->where(['is_newest' => Message::VALUE_YES])
                ->andWhere(['>=', 'created_at', Yii::$app->user->identity->created_at])
                ->andWhere('receiver_id = 0 or receiver_id = :receiverId', [':receiverId' => Yii::$app->user->getId()])
                ->groupBy(['type']);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->setPagination(false);
        
        return static::formatSuccessResult($dataProvider);
    }
}