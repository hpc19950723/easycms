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
     * @param mixed $id 取值 'self' 或 instgram user id
     * @return array
     */
    public function actionBaseInfo($id = 'self')
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $instagram = Yii::$app->authClientCollection->getClient('instagram');
        $instagram->setAccessToken(['params' => ['access_token' => $instagramAccessToken]]);
        $instagramUser = $instagram->getUserBaseInfo($id);
        return static::formatSuccessResult($instagramUser['data']);
    }
    
    /**
     * 我关注但并没有关注我的人
     * @return array
     */
    public function actionFollowButNotFollowed()
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $followAndFollowed = Yii::createObject([
            'class' => 'common\modules\instagram\components\FollowAndFollowed',
            'accessToken' => $instagramAccessToken
        ]);

        //我关注的人
        $selfFollows = $followAndFollowed->getFollows();
        //关注我的人
        $selfFollowedBy = $followAndFollowed->getFollowedBy();
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
        
        return static::formatSuccessResult($data);
    }
    
    /**
     * 关注我但我并没有关注的人
     * @return array
     */
    public function actionFollowedButNotFollow()
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $followAndFollowed = Yii::createObject([
            'class' => 'common\modules\instagram\components\FollowAndFollowed',
            'accessToken' => $instagramAccessToken
        ]);
        
        //我关注的人
        $selfFollows = $followAndFollowed->getFollows();
        //关注我的人
        $selfFollowedBy = $followAndFollowed->getFollowedBy();
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
        
        return static::formatSuccessResult($data);
    }
    
    /**
     * 关注
     * @param int $id Instagram User Id
     * @return array
     */
    public function actionFollow($id)
    {
        $this->relationshop($id, 'follow');
        
        return static::formatSuccessResult();
    }
    
    /**
     * 取消关注
     * @param int $id Instagram User Id
     * @return array
     */
    public function actionUnfollow($id)
    {
        $this->relationshop($id, 'unfollow');
        
        return static::formatSuccessResult();
    }
    
    public function actionBaseData()
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $instagramUser = Yii::createObject([
            'class' => 'common\modules\instagram\components\InstagramUser',
            'accessToken' => $instagramAccessToken
        ]);
        $statisticData = $instagramUser->getStatisticData();
        return $this->formatSuccessResult($statisticData);
    }
    
    /**
     * 关注或取消关注
     * @param int $id Instagram User Id
     * @param string $action
     */
    public function relationshop($id, $action)
    {
        $user = Yii::$app->user->identity;
        $instagramAccessToken = $user->instagram_access_token;
        $instagram = Yii::$app->authClientCollection->getClient('instagram');
        $instagram->setAccessToken(['params' => ['access_token' => $instagramAccessToken]]);
        $instagram->{$action}($id);
    }
}