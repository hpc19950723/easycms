<?php

namespace common\modules\admin\controllers;

use Yii;
use common\modules\admin\models\AdminMenu;
use yii\data\ArrayDataProvider;
use common\modules\admin\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\modules\admin\models\searches\AdminMenuSearch;

/**
 * AdminMenuController implements the CRUD actions for AdminMenu model.
 */
class AdminMenuController extends BaseController
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
     * Lists all AdminMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single AdminMenu model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AdminMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminMenu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $parentId = Yii::$app->request->post()['AdminMenu']['parent_id'];
            if(0 < $parentId) {
                //修改父类children_count加1
                $model = $this->findModel($parentId)->updateCounters(['children_count' => 1]);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldParentId = $model->parent_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $parentId = Yii::$app->request->post()['AdminMenu']['parent_id'];
            //如果修改了所属菜单,则需要修改对应的children_count
            if($oldParentId != $parentId){
                if(0 < $parentId) {
                    //修改父类children_count加1
                    $model = $this->findModel($parentId)->updateCounters(['children_count' => 1]);
                }
                
                if(0 < $oldParentId) {
                    //修改源父类children_count减1
                    $model = $this->findModel($oldParentId)->updateCounters(['children_count' => -1]);
                }
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdminMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->parent_id > 0) {
            $this->findModel($model->parent_id)->updateCounters(['children_count' => -1]);
        }
        $model->delete();

        return $this->redirect(['index']);
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
        if (($model = AdminMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
