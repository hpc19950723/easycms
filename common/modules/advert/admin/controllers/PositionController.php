<?php
namespace common\modules\advert\admin\controllers;

use Yii;
use common\modules\advert\models\forms\AdvertPositionForm;
use common\modules\advert\models\AdvertPosition;
use yii\data\ActiveDataProvider;

class PositionController extends \common\modules\admin\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AdvertPosition::find()
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    
    /**
     * 创建广告位
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdvertPositionForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
    
    
    public function actionUpdate($id)
    {
        $model = new AdvertPositionForm();
        $model->initData($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
}