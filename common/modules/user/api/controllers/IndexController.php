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
            'optional' => ['create', 'login', 'reset-password', 'wechat-login']
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
     * 微信三方登陆
     * @return array
     */
    public function actionWechatLogin()
    {
        $code = trim(Yii::$app->request->post('code'));
        if(empty($code)){
            return self::formatResult(10002, Yii::t('error', 'Invalid params request'));
        }
        
        $model = new ThirdPartLoginForm();
        $type = Yii::$app->request->post('type', 'login');
        switch ($type) {
            case 'login':
                try {
                    $weixin = Yii::$app->authClientCollection->getClient('weixin');
                    $weixin->fetchAccessToken($code);
                    $userAttributes = $weixin->getUserAttributes();

                    $data = [
                        'nickname'      => $userAttributes['nickname'],
                        'avatar'        => $userAttributes['headimgurl'],
                        'thirdPartId'   => $userAttributes['unionid'],
                        'gender'        => $userAttributes['sex']
                    ];

                    $model->setScenario(ThirdPartLoginForm::SCENARIOS_WECHAT_LOGIN);
                    if ($model->load($data, '') && $token = $model->login()) {
                        return self::formatSuccessResult($data = ['token' => $token]);
                    } else {
                        Yii::$app->cache->set($code, $data, 1800);
                        return self::formatResult(10207, '请先绑定当前应用账号');
                    }
                } catch(Exception $e) {
                    return self::formatResult(10209, '微信授权登陆失败');
                }
                break;
            case 'bind':
                $data = Yii::$app->cache->get($code);
                if ($data === false) {
                    return self::formatResult(10206, '微信授权过期,请重新授权');
                }

                $post = Yii::$app->request->post();
                $post['code'] = $post['secrity_code'];
                unset($post['secrity_code']);
                $data = array_merge($data, $post);
                $model->setScenario(ThirdPartLoginForm::SCENARIOS_WECHAT_BIND);
                if ($model->load($data, '') && $token = $model->bind()) {
                    return self::formatSuccessResult($data = ['token' => $token]);
                } else {
                    return self::formatResult(10208, Tools::getFirstError($model->errors, '微信账号绑定失败'));
                }
                break;
            default:
                return self::formatResult(10002, Yii::t('error', 'Invalid params request'));
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