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
        $selfFollows = $instagram->api('v1/users/self/follows', 'GET');
        //关注我的人
        $selfFollowedBy = $instagram->api('v1/users/self/followed-by', 'GET');
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
            'follows' => $selfFollowNotFollowed
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
        $selfFollows = $instagram->api('v1/users/self/follows', 'GET');
        //关注我的人
        $selfFollowedBy = $instagram->api('v1/users/self/followed-by', 'GET');
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
            'follows' => $followedButNotFollow
        ];
        
        return $this->formatSuccessResult($data);
    }
}