<?php

namespace common\modules\user\api\controllers;

use Yii;
use common\modules\core\api\components\BaseController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use common\modules\user\models\User;
use common\modules\user\models\forms\RegisterForm;
use common\modules\user\models\forms\LoginForm;
use common\modules\user\models\forms\ResetPasswordForm;
use common\modules\user\models\forms\UpdatePasswordForm;
use common\modules\user\models\forms\UserForm;
use yii\web\UploadedFile;
use common\modules\core\components\Tools;
use yii\web\NotFoundHttpException;
use common\modules\user\models\forms\ThirdPartLoginForm;
use yii\base\Exception;

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
            'optional' => ['create', 'login', 'reset-password', 'instagram-login', 'wechat-login', 'qq-login', 'weibo-login']
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
     * Instagram登录
     * @return array
     */
    public function actionInstagramLogin()
    {
        $code = trim(Yii::$app->request->get('code'));
        if(empty($code)){
            return self::formatResult(10002, Yii::t('error', 'Invalid params request'));
        }
        
        $model = new ThirdPartLoginForm();
        try {
            $instagram = Yii::$app->authClientCollection->getClient('instagram');
            $instagram->fetchAccessToken($code);
            $accessToken = $instagram->getAccessToken();
            $user = $accessToken->getParam('user');
            $data = [
                'nickname' => $user['username'],
                'avatar' => $user['profile_picture'],
                'instagram_user_id' => $user['id'],
                'instagram_access_token' => $accessToken->getParam('access_token')
            ];

            if ($model->load($data, '') && $token = $model->login()) {
                return self::formatSuccessResult($data = ['token' => $token]);
            } else {
                Yii::$app->cache->set($accessToken, $data, 1800);
                return self::formatResult(10207, '请先绑定当前应用账号');
            }
        } catch(Exception $e) {
            echo $e->getMessage();exit;
            return self::formatResult(10216, 'instagram login fail');
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
        $model->initData(Yii::$app->user->getId());
        
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