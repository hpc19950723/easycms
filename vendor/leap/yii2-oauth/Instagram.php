<?php

namespace leap\oauth;

use Yii;
use yii\authclient\OAuth2;
use yii\web\HttpException;

class Instagram extends OAuth2
{
    public $authUrl = 'https://api.instagram.com/oauth/authorize';
    
    public $tokenUrl = 'https://api.instagram.com/oauth/access_token';
    
    public $apiBaseUrl = 'https://api.instagram.com';
    
    public $validateAuthState = false;
    
    public function initUserAttributes() {
        return $this->api('v1/users/self/', 'GET');
    }
    
    /**
     * 获取我关注的人
     * @param boolean $readCache 是否从缓存中读取
     * @return array
     */
    public function getSelfFollows($readCache = true)
    {
        return $this->cacheApiData('v1/users/self/follows', 'self_follows_', 600, $readCache);
    }
    
    /**
     * 获取关注我的人
     * @param boolean $readCache 是否从缓存中读取
     * @return array
     */
    public function getSelfFollowedBy($readCache = true)
    {
        return $this->cacheApiData('v1/users/self/followed-by', 'self_followed_by_', 600, $readCache);
    }
    
    public function cacheApiData($apiSubUrl, $cacheKeyPrefix, $duration, $readCache = true)
    {
        $accessToken = $this->getAccessToken()->getParam('access_token');
        $cacheKey = $cacheKeyPrefix . md5($accessToken);
        $data = Yii::$app->cache->get($cacheKey);
        if(!$readCache || empty($data)) {
            $data = $this->api($apiSubUrl, 'GET');
            if(isset($data['meta']['code']) && $data['meta']['code'] == 200) {
                Yii::$app->cache->set($cacheKey, $data, $duration);
            }
        }
        return $data;
    }
    
    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'instagram';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'instagram';
    }
}