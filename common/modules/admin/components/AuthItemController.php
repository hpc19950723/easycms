<?php

namespace common\modules\admin\components;

use Yii;
use common\modules\admin\models\AdminAuthItem;
use common\modules\admin\models\searchs\AdminAuthItemSearch;
use common\modules\admin\models\forms\ItemForm;
use yii\rbac\Item;
use yii\data\ActiveDataProvider;
use common\modules\admin\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use common\modules\core\components\Tools;

/**
 * AuthController implements the CRUD actions for AdminAuthItem model.
 */
class AuthItemController extends BaseController
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
        $searchModel = new AdminAuthItemSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('/auth/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    

    /**
     * Displays a single AdminAuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('/auth/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 创建角色权限
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ItemForm();
        //设置场景
        $model->setScenario(ItemForm:: SCENARIOS_CREATE);
        if ($model->load(Tools::getPost(['type' => $this->type], $model->formName())) && $model->validate()) {
            $model->addItem();
            return $this->redirect(['index']);
        } else {
            if($this->type == Item::TYPE_ROLE) {
                $models = AdminAuthItem::find()->where('type = ' . Item::TYPE_PERMISSION)
                        ->andWhere(['not like', 'name', '/%', false])
                        ->all();
            } else {
                $models = AdminAuthItem::find()->where('type = ' . Item::TYPE_PERMISSION)
                        ->andWhere(['like', 'name', '/%', false])
                        ->all();
            }
                        
            $dataProvider = new ArrayDataProvider([
                'allModels' => $models,
                'pagination' => [
                    'pageSize' => 2000,
                ],
            ]);
            
            return $this->render('/auth/create', [
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
    public function actionUpdate($id)
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
            if($this->type == Item::TYPE_ROLE) {
                $models = AdminAuthItem::find()->where('type = ' . Item::TYPE_PERMISSION)
                        ->andWhere(['not like', 'name', '/%', false])
                        ->all();
            } else {
                $models = AdminAuthItem::find()->where('type = ' . Item::TYPE_PERMISSION)
                        ->andWhere(['like', 'name', '/%', false])
                        ->all();
            }
                        
            $dataProvider = new ArrayDataProvider([
                'allModels' => $models,
                'pagination' => [
                    'pageSize' => 2000,
                ],
            ]);
            
            return $this->render('/auth/create', [
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
    public function actionDelete($id)
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
