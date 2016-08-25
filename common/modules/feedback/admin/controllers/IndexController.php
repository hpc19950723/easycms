<?php
namespace common\modules\feedback\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\modules\feedback\models\Feedback;

class IndexController extends \common\modules\admin\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Feedback::find()->orderBy('created_at desc')
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
    

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    

    protected function findModel($id)
    {
        if (($model = Feedback::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}