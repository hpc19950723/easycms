<?php

namespace common\modules\admin\controllers;

use Yii;
use common\modules\admin\models\AdminAuthItem;
use common\modules\admin\models\forms\ItemForm;
use yii\data\ActiveDataProvider;
use common\modules\admin\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthController implements the CRUD actions for AdminAuthItem model.
 */
class AuthController extends BaseController
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
     * Lists all AdminAuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AdminAuthItem::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function actionRole()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AdminAuthItem::find()->where('type=' . AdminAuthItem::T_ROLE),
        ]);

        return $this->render('role', [
            'dataProvider' => $dataProvider,
        ]);
    }
    

    /**
     * Displays a single AdminAuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionViewItem($id)
    {
        return $this->render('viewItem', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 创建角色权限
     * @return mixed
     */
    public function actionCreateItem()
    {
        $model = new ItemForm();
        //设置场景
        $model->setScenario(ItemForm:: SCENARIOS_CREATE);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->addItem();
            if('role' == Yii::$app->request->get('type')) {
                return $this->redirect(['role']);
            } else {
                return $this->redirect(['index']);
            }
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => AdminAuthItem::find()->where('type = ' . AdminAuthItem::T_PERMISSION),
                'pagination' => [
                    'pageSize' => 100
                ]
            ]);
            return $this->render('createItem', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    

    /**
     * 更新角色权限
     * @param string $id
     * @return mixed
     */
    public function actionUpdateItem($id)
    {
        $model = new ItemForm();
        $model = $model->getItem($id);
        
        $model->setScenario(ItemForm::SCENARIOS_UPDATE);
        if(($post = Yii::$app->request->post()) && empty($post['ItemForm']['children'])) {
            $post['ItemForm']['children'] = [];
        }
        if ($model->load($post) && $model->validate()) {
            $model->updateItem($id);
            if('role' == Yii::$app->request->get('type')) {
                return $this->redirect(['role']);
            } else {
                return $this->redirect(['index']);
            }
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => AdminAuthItem::find()->where('type = ' . AdminAuthItem::T_PERMISSION)->orderBy(['description' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 100
                ]
            ]);
            
            return $this->render('updateItem', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing AdminAuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteItem($id)
    {
        $model = new ItemForm();
        $model->setScenario(ItemForm:: SCENARIOS_DELETE);
        $model->name = $id;
        $model->romoveItem();
        
        if('role' == Yii::$app->request->get('type')) {
            return $this->redirect(['role']);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the AdminAuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AdminAuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminAuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
