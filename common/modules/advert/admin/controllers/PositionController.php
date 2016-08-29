<?php
namespace common\modules\advert\admin\controllers;

use Yii;
use common\modules\advert\models\forms\AdvertPositionForm;
use common\modules\advert\models\AdvertPosition;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class PositionController extends \common\modules\admin\components\BaseController
{
    /**
     * 广告位列表
     * @return mixed
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
    
    
    /**
     * 更新广告位
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new AdvertPositionForm();
        $model->initData($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
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
            $model->status = $model->status == AdvertPosition::STATUS_ACTIVE ? AdvertPosition::STATUS_INACTIVE : AdvertPosition::STATUS_ACTIVE;
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
        $this->findModel($id)->delete();
        return $this->redirect('index');
    }
    
    
    protected function findModel($id)
    {
        $model = AdvertPosition::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('页面不存在');
        }
        
        return $model;
    }
}