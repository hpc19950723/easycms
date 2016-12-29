<?php
namespace common\modules\instagram\api\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;

class IndexController extends \common\modules\core\api\components\BaseController
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
        ];
        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        
    }
    
    /**
     * 获取登录用户基本信息
     * @return array
     */
    public function actionBaseInfo()
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $instagram = Yii::$app->authClientCollection->getClient('instagram');
        $instagram->setAccessToken(['params' => ['access_token' => $instagramAccessToken]]);
        $instagramUser = $instagram->initUserAttributes();
        return $this->formatSuccessResult($instagramUser['data']);
    }
    
    /**
     * 我关注但并没有关注我的人
     * @return array
     */
    public function actionFollowButNotFollowed()
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $instagram = Yii::$app->authClientCollection->getClient('instagram');
        $instagram->setAccessToken(['params' => ['access_token' => $instagramAccessToken]]);
        //我关注的人
        $selfFollows = $instagram->getSelfFollows();
        //关注我的人
        $selfFollowedBy = $instagram->getSelfFollowedBy();
        $followers = [];
        if(!empty($selfFollowedBy['data'])) {
            foreach($selfFollowedBy['data'] as $follower) {
                $followers[$follower['id']] = $follower;
            }
        }
        
        //计算出我关注但并没有关注我的人
        $selfFollowNotFollowed = [];
        if(!empty($selfFollows['data'])) {
            foreach($selfFollows['data'] as $follow) {
                if(!isset($followers[$follow['id']])) {
                    $selfFollowNotFollowed[] = $follow;
                }
            }
        }
        
        $data = [
            'total' => count($selfFollowNotFollowed),
            'users' => $selfFollowNotFollowed
        ];
        
        return $this->formatSuccessResult($data);
    }
    
    /**
     * 关注我但我并没有关注的人
     * @return array
     */
    public function actionFollowedButNotFollow()
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $instagram = Yii::$app->authClientCollection->getClient('instagram');
        $instagram->setAccessToken(['params' => ['access_token' => $instagramAccessToken]]);
        //我关注的人
        $selfFollows = $instagram->getSelfFollows();
        //关注我的人
        $selfFollowedBy = $instagram->getSelfFollowedBy();
        $follows = [];
        if(!empty($selfFollows['data'])) {
            foreach($selfFollows['data'] as $follow) {
                $follows[$follow['id']] = $follow;
            }
        }
        
        //计算出关注我但我并没有关注的人
        $followedButNotFollow = [];
        if(!empty($selfFollowedBy['data'])) {
            foreach($selfFollowedBy['data'] as $follower) {
                if(!isset($follows[$follower['id']])) {
                    $followedButNotFollow[] = $follower;
                }
            }
        }
        
        $data = [
            'total' => count($followedButNotFollow),
            'users' => $followedButNotFollow
        ];
        
        return $this->formatSuccessResult($data);
    }
}