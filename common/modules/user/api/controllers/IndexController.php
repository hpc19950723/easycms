<?php

namespace common\modules\user\api\controllers;

use Yii;
use common\modules\core\api\components\BaseController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\user\models\User;
use common\modules\user\models\form\RegisterForm;
use common\modules\user\models\form\LoginForm;
use common\modules\user\models\form\ResetPasswordForm;
use common\modules\user\models\form\UpdatePasswordForm;
use common\modules\user\models\form\UserForm;
use yii\web\UploadedFile;
use common\modules\core\components\Tools;
use yii\web\NotFoundHttpException;

class IndexController extends BaseController
{
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                QueryParamAuth::className(),
            ],
            'optional' => ['create', 'login', 'reset-password']
        ];
        return $behaviors;
    }
    
    
    /**
     * 用户注册
     */
    public function actionCreate()
    {
        $model = new RegisterForm();
        
        $model->setScenario(RegisterForm::SCENARIOS_REGISTER);
        if($model->load(Yii::$app->request->post(), '') && $model->save()) {
            $data = [
                'accessToken' => $model->getAccessToken()
            ];
            return self::formatSuccessResult($data);
        } else {
           return self::formatResult(10202, Tools::getFirstError($model->errors));
        }
    }
    
    
    /**
     * 用户登录
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post(), '') && ($accessToken = $model->getAccessToken())) {
            $data = [
                'accessToken' => $accessToken,
            ];
            return self::formatSuccessResult($data);
        } else {
            return self::formatResult(10200, Tools::getFirstError($model->errors));
        }
    }
    
    
    /**
     * 重置密码
     */
    public function actionResetPassword()
    {
        $model = new ResetPasswordForm();
        
        if($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return self::formatSuccessResult();
        } else {
           return self::formatResult(10203, Tools::getFirstError($model->errors));
        }
    }
    
    
    /*
     * 更改密码
     * @return array
     */
    public function actionUpdatePassword()
    {
        $model = new UpdatePasswordForm();
        $model->setScenario(UpdatePasswordForm::SCENARIOS_API_UPDATE_PASSWORD);
        if($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return self::formatSuccessResult();
        } else {
            return self::formatResult(10204, Tools::getFirstError($model->errors));
        }
    }
    
    
    /**
     * 获取用户基本信息
     * @return array
     */
    public function actionView()
    {
        $model = $this->findModel(Yii::$app->user->getId());
        return self::formatSuccessResult($model);
    }
    
    
    /**
     * 更新用户信息
     * @return array
     */
    public function actionUpdate()
    {
        $model = new UserForm();
        $model->initUser(Yii::$app->user->getId());
        
        $post = Yii::$app->request->post();
        $post['avatar'] = UploadedFile::getInstanceByName('avatar');

        $model->setScenario(UserForm::SCENARIOS_API_UPDATE);
        if($model->load($post,'') && $model->save()) {
            return self::formatSuccessResult();
        } else {
            return self::formatResult(10205, Tools::getFirstError($model->errors));
        }
    }
    
    
    protected function findModel($id)
    {
        $model = User::findOne(['user_id' => $id, 'status' => User::STATUS_ACTIVE]);
        if($model === null) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        
        return $model;
    }
}