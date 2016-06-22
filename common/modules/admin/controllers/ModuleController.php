<?php

namespace common\modules\admin\controllers;

use Yii;
use common\modules\admin\models\Module;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ModuleController extends \common\modules\admin\components\BaseController
{
    /**
     * 模块列表
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Module::find()
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    
    /**
     * 更新模块状态,开启关闭模块
     */
    public function actionUpdateStatus($id)
    {
        if(Yii::$app->request->isAjax) {
            $type = Yii::$app->request->get('type');
            $model = $this->findModel($id);
            $model->$type = $model->$type > 0 ? 0 : 1;
            $model->save();
            return self::formatSuccessResult();
        }
    }
    
    
    protected function findModel($id)
    {
        if (($model = Module::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}