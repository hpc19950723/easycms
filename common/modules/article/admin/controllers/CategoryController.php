<?php

namespace common\modules\article\admin\controllers;

use Yii;
use common\modules\article\models\ArticleCategory;
use common\modules\article\models\forms\ArticleCategoryForm;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * CategoryController implements the CRUD actions for Categorys model.
 */
class CategoryController extends \common\modules\admin\components\BaseController
{
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
        return array_merge(parent::behaviors(), $behaviors);
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $models = ArticleCategory::find()->where(['parent_id' => 0])
                ->orderBy(['position' => SORT_ASC])->all();
        
        $sortedModels = [];
        foreach($models as $model) {
            $sortedModels[] = $model;
            $childMenuModels = ArticleCategory::find()->where(['parent_id' => $model->category_id])
                ->orderBy(['position' => SORT_ASC])->all();
            if(count($childMenuModels)) {
                $sortedModels = array_merge($sortedModels, $childMenuModels);
            }
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $sortedModels,
            'key' => 'category_id',
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    

    /**
     * 创建分类
     */
    public function actionCreate()
    {
        $model = new ArticleCategoryForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 更新分类
     */
    public function actionUpdate($id)
    {
        $model = new ArticleCategoryForm();
        $model->initData($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'categoryId' => $id
            ]);
        }
    }

    /**
     * 删除分类
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->children_count == 0 && $model->items_count == 0) {
            $model->delete();
        } else {
            Yii::$app->session->setFlash('error', '请先删除该分类下的所有子分类和文章');
        }
        
        return $this->redirect(['index']);
    }
    
    
    /**
     * 更新状态
     */
    public function actionUpdateStatus($id)
    {
        if(Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $model->status = $model->status == ArticleCategory::STATUS_ACTIVE ? ArticleCategory::STATUS_INACTIVE : ArticleCategory::STATUS_ACTIVE;
            $model->save();
            return self::formatSuccessResult();
        }
    }

    
    /**
     * Finds the AdminMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AdminMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
