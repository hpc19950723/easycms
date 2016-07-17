<?php

namespace common\modules\admin\controllers;

use Yii;
use common\modules\admin\models\forms\UpdatePasswordForm;
use common\modules\admin\models\Admin;
use common\modules\admin\models\forms\AccountForm;
use yii\data\ActiveDataProvider;

class AccountController extends \common\modules\admin\components\BaseController
{
    /**
     * 子账号列表
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Admin::find()->where('status > :status',[':status' => 0])->andWhere('user_type = ' . Admin::USER_TYPE_NORMALADMIN);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    /**
     * 创建账号
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountForm();

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
        
    }
    
    
    /**
     * 更新账户信息
     * @param $id 索引id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new AccountForm();
        $model = $model->getAccount($id);

        if($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }
    
    
    /**
     * 删除账号
     * @param type $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = 0;
        $model->save();
        return $this->redirect(['index']);
    }
    
    
    /**
     * 更新管理员密码
     * @return mixed
     */
    public function actionUpdatePassword()
    {
        $model = new UpdatePasswordForm();
        
        $post = Yii::$app->request->post();
        if(empty($post['Admin']['password'])) {
            unset($post['Admin']['password']);
        }
        if($model->load($post) && $model->validate()) {
            $model->save();
        }
        return $this->render('updatePassword',[
            'model' => $model,
        ]);
    }
    
    
    /**
     * 通过主键ID获取Model对象
     * @param type $id
     * @return object
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
