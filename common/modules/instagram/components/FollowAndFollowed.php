<?php

namespace common\modules\instagram\components;

use Yii;
use yii\base\Component;
use common\modules\instagram\models\InstagramUser;

class FollowAndFollowed extends Component
{
    public $accessToken;
    
    public $instagram;
    
    public function __construct($config = [])
    {
        parent::__construct($config);
        if(!$this->instagram instanceof \yii\authclient\OAuth2) {
            $this->init();
        }
    }
    
    public function init()
    {
        $this->instagram = Yii::$app->authClientCollection->getClient('instagram');
        $this->instagram->setAccessToken(['params' => ['access_token' => $this->accessToken]]);
    }
    
    public function getFollows()
    {
        return $this->getCache('getSelfFollows', true, 600, function($users){
            InstagramUser::batchInsert($users);
        });
    }
    
    public function getFollowedBy()
    {
        return $this->getCache('getSelfFollowedBy', true, 600, function($users){
            InstagramUser::batchInsert($users);
        });
    }
    
    public function getCache($method, $readCache = true, $duration = 0, $callback = null)
    {
        $cacheKey = $method . '_' . md5($this->accessToken);
        $data = Yii::$app->cache->get($cacheKey);
        if(!$readCache || empty($data)) {
            $data = $this->instagram->$method();
            if($callback instanceof \Closure && isset($data['meta']['code']) && $data['meta']['code'] == 200) {
                call_user_func($callback, $data['data']);
            }
            if($readCache && isset($data['meta']['code']) && $data['meta']['code'] == 200) {
                Yii::$app->cache->set($cacheKey, $data, $duration);
            }
        }
        return $data;
    }
}