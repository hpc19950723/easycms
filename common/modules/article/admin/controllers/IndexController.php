<?php
namespace common\modules\article\admin\controllers;

use Yii;
use common\modules\article\models\forms\ArticleForm;
use common\modules\article\models\ArticleCategory;
use yii\web\UploadedFile;
use common\modules\article\models\searches\ArticleSearch;
use common\modules\article\models\Article;
use yii\web\NotFoundHttpException;

class IndexController extends \common\modules\admin\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
    
    
    /**
     * 创建文章内容
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleForm();
        $model->setScenario(ArticleForm::SCENARIOS_CREATE);
        if(Yii::$app->request->isPost) {
            $image = UploadedFile::getInstance($model, 'image');
            $model->load(Yii::$app->request->post());
            $model->image = $image;
        }
        
        if (Yii::$app->request->isPost && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    
    /**
     * 更新文章
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new ArticleForm();
        $model->initData($id);
        
        $model->setScenario(ArticleForm::SCENARIOS_UPDATE);
        if(Yii::$app->request->isPost) {
            $image = UploadedFile::getInstance($model, 'image');
            $model->load(Yii::$app->request->post());
            $model->image = $image;
        }
        
        if (Yii::$app->request->isPost && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'articleId' => $id
            ]);
        }
    }
    
    
    public function actionDeleteImage()
    {
        if(Yii::$app->request->isAjax) {
            $articleId = Yii::$app->request->get('article_id');
            $model = $this->findModel($articleId);
            $model->image = null;
            $model->save();
            @unlink(Yii::getAlias('@uploads' . $model->image));
            return self::formatSuccessResult();
        }
    }
    
    
    /**
     * 删除文章
     * @param int $id
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        @unlink(Yii::getAlias('@uploads' . $model->image));
        $model->delete();
        return $this->redirect('index');
    }
    
    
    /**
     * 更新状态
     */
    public function actionUpdateStatus($id)
    {
        if(Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $model->status = $model->status == Article::STATUS_ACTIVE ? Article::STATUS_INACTIVE : Article::STATUS_ACTIVE;
            $model->save();
            return self::formatSuccessResult();
        }
    }
    
    
    protected function findModel($id)
    {
        $model = Article::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('页面不存在');
        }
        
        return $model;
    }
}