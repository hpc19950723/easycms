<?php
namespace common\modules\advert\admin\controllers;

use Yii;
use common\modules\advert\models\Advert;
use yii\data\ActiveDataProvider;
use common\modules\advert\models\forms\AdvertForm;
use yii\web\UploadedFile;

class IndexController extends \common\modules\admin\components\BaseController
{
    /**
     * 广告列表
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Advert::find()
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
        $model = new AdvertForm();
        
        $model->setScenario(AdvertForm::SCENARIOS_CREATE);
        if(Yii::$app->request->isPost) {
            $image = UploadedFile::getInstance($model, 'image');
            $model->load(Yii::$app->request->post());
            $model->image = $image;
        }
        
        if (Yii::$app->request->isPost && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
    
    
    /**
     * 更新广告位
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new AdvertForm();
        $model->initData($id);
        
        $model->setScenario(AdvertForm::SCENARIOS_UPDATE);
        if(Yii::$app->request->isPost) {
            $image = UploadedFile::getInstance($model, 'image');
            $model->load(Yii::$app->request->post());
            $model->image = $image;
        }
        
        if (Yii::$app->request->isPost && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }
    
    
    /**
     * 删除广告位
     * @param int $id
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect('index');
    }
    
    
    protected function findModel($id)
    {
        $model = Advert::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('页面不存在');
        }
        
        return $model;
    }
}