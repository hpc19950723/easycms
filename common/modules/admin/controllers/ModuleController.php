<?php

namespace common\modules\admin\controllers;

use Yii;
use common\modules\admin\models\Module;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\modules\admin\models\forms\ModuleForm;
use yii\helpers\FileHelper;
use common\modules\core\components\Tools;

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
    
    
    /**
     * 模块打包
     */
    public function actionZip($id)
    {
        $model = $this->findModel($id);
        $config = <<<EOF
<?php
return [
    'name' => '$model->name',
    'title' => '$model->title',
    'dir' => '$model->dir',
    'settings' => '$model->settings',
    'version' => '$model->version',
    'has_api' => '$model->has_api',
    'has_admin' => '$model->has_admin',
    'status' => '$model->status',
];
EOF;
        $uploadedFileDir = Yii::getAlias('@downloader/' . $model->name . '_' . $model->version. '_' . date('YmdHi'));
        $modulesDir = $uploadedFileDir . '/common/modules';
        $themesDir = $uploadedFileDir . '/themes/backend/base/views';
        FileHelper::createDirectory($modulesDir, 0755, true);
        FileHelper::createDirectory($themesDir, 0755, true);
        file_put_contents($uploadedFileDir . '/config.php', $config);
        Tools::xCopy(Yii::getAlias('@common/modules/' . $model->name), $modulesDir . '/' . $model->name);
        Tools::xCopy(Yii::getAlias('@themes/backend/base/views/' . $model->name), $themesDir . '/' . $model->name);
        Tools::zipDir($uploadedFileDir);
        Yii::$app->getSession()->setFlash('success', "'$model->title'已经打包完成");
        return $this->redirect(['index']);
    }
    
    
    /**
     * 创建模块
     */
    public function actionCreate()
    {
        $model = new ModuleForm();
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
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