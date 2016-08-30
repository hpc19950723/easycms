<?php
namespace common\modules\advert\admin\controllers;

use Yii;
use common\modules\advert\models\Advert;
use yii\data\ActiveDataProvider;
use common\modules\advert\models\forms\AdvertForm;
use yii\web\UploadedFile;
use common\modules\advert\models\AdvertPosition;

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
     * 创建广告
     * @return mixed
     */
    public function actionCreate()
    {
        $advertPosition = AdvertPosition::find()->select(['name', 'position_id'])->indexBy('position_id')->column();
        if (empty($advertPosition)) {
            Yii::$app->session->setFlash('error', '广告位不存在,请创建广告位');
            return $this->redirect(['index']);
        }
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
                'model' => $model,
                'advertPosition' => $advertPosition
            ]);
        }
    }
    
    
    /**
     * 更新广告
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
     * 更新状态
     */
    public function actionUpdateStatus($id)
    {
        if(Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $model->status = $model->status == Advert::STATUS_ACTIVE ? Advert::STATUS_INACTIVE : Advert::STATUS_ACTIVE;
            $model->save();
            return self::formatSuccessResult();
        }
    }
    
    
    /**
     * 删除广告位
     * @param int $id
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        @unlink(Yii::getAlias('@uploads' . $model->image));
        $model->delete();
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