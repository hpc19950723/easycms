<?php

namespace common\modules\module\controllers;

use Yii;
use common\modules\module\models\Module;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\modules\module\models\forms\ModuleForm;
use yii\helpers\FileHelper;
use common\modules\core\components\Tools;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\modules\core\components\FileUploader;
use common\modules\core\components\Migrate;

class IndexController extends \common\modules\admin\components\BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'import' => ['post'],
                ],
            ],
        ];
    }
    
    
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
    'enabled_api' => '$model->enabled_api',
    'enabled_admin' => '$model->enabled_admin',
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
     * 模块导入
     * @return type
     */
    public function actionImport()
    {
        $importModule = UploadedFile::getInstanceByName('importModule');
        if ($importModule) {
            $fileUploader = new FileUploader([
                'uploadedFileRoot' => '@downloader',
                'uploadedFile' => $importModule,
                'UploadedFileName' => $importModule->name,
            ]);
            $fileName = $fileUploader->save();
            if ($fileName) {
                $path = Yii::getAlias('@downloader/' . $fileName);
                Tools::unZip($path);
                
                $pathinfo = pathinfo($path);
                $dirPath = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'];
                $config = require($dirPath . DIRECTORY_SEPARATOR . 'config.php');
                $modules = Module::getAll();
                if (!isset($modules[$config['name']])) {
                    $moduleModel = new Module();
                    $moduleModel->attributes = $config;
                    $moduleModel->save();
                    
                    Tools::xCopy($dirPath . DIRECTORY_SEPARATOR . 'common', Yii::getAlias('@common'));
                    Tools::xCopy($dirPath . DIRECTORY_SEPARATOR . 'themes', Yii::getAlias('@themes'));
                    //数据迁移
                    $migrate = new Migrate([
                        'migrationPath' => '@common/modules/' . $config['name'] . '/migrations'
                    ]);
                    $migrate->up();
                    Yii::$app->getSession()->setFlash('success', '导入模块成功');
                } else {
                    Yii::$app->getSession()->setFlash('error', '您导入的模块已经存在');
                }
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->setFlash('error', '模块导入失败');
                return $this->redirect(['index']);
            }
        }
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
    
    
    /**
     * 模块删除
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(!$model->deletable) {
            Yii::$app->getSession()->setFlash('error', "'$model->title'不可删除");
        } else {
            $module = $model->toArray();
            $model->delete();

            $migrate = new Migrate([
                'migrationPath' => '@common/modules/' . $module['name'] . '/migrations'
            ]);
            $migrate->down();

            $moduleDir = Yii::getAlias('@common/modules/' . $module['name']);
            $themeDir = Yii::getAlias('@themes/backend/base/views/' . $module['name']);
            FileHelper::removeDirectory($moduleDir);
            FileHelper::removeDirectory($themeDir);

            Yii::$app->getSession()->setFlash('success', '模块删除成功');
        }
        return $this->redirect(['index']);
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