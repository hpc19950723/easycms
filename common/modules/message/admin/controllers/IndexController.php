<?php
namespace common\modules\message\admin\controllers;

use Yii;
use common\modules\message\models\Message;
use common\modules\message\models\forms\MessageForm;
use common\modules\message\models\searches\MessageSearch;
use yii\web\UploadedFile;
use common\modules\core\components\Tools;

class IndexController extends \common\modules\admin\components\BaseController
{
    /**
     * 消息列表
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
    
    
    /**
     * 创建消息
     * @return mixed
     */
    public function actionCreate()
    {
        $type = Yii::$app->request->get('type', Message::TYPE_SYSTEM);
        if (!in_array($type, array_keys(Message::getTypes()))) {
            Yii::$app->session->setFlash('error', '创建消息类型不存在');
            return $this->redirect(['index']);
        }
        
        $model = new MessageForm();
        $model->setScenario(MessageForm::SCENARIOS_BACKEND_CREATE);
        if($model->load(Tools::getPost([
                'type' => $type, 
                'image' => UploadedFile::getInstance($model, 'image')
            ], $model->formName())) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'type' => $type
        ]);
    }
    
    
    /**
     * 消息更新
     * @param type $id
     */
    public function actionUpdate($id)
    {
        $model = new MessageForm();
        $model->initData($id);
        
        $model->setScenario(MessageForm::SCENARIOS_BACKEND_UPDATE);
        if($model->load(Tools::getPost([
                'image' => UploadedFile::getInstance($model, 'image')
            ], $model->formName())) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }
    
    
    public function actionDelete($id)
    {
        $this->findModel($id)
                ->delete();
        return $this->redirect(['index']);
    }
    
    
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}