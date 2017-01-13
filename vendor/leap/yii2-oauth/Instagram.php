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
    
    public function getUserBaseInfo($id) {
        return $this->api('v1/users/' . $id . '/', 'GET');
    }
    
    /**
     * 获取我关注的人
     * @return array
     */
    public function getSelfFollows()
    {
        return $this->api('v1/users/self/follows', 'GET');
    }
    
    /**
     * 获取关注我的人
     * @return array
     */
    public function getSelfFollowedBy()
    {
        return $this->api('v1/users/self/followed-by', 'GET');
    }
    
    /**
     * 关注
     * @param int $instagramUserId
     * @return array
     */
    public function follow($instagramUserId)
    {
        return $this->api('v1/users/' . $instagramUserId . '/relationship', 'POST', ['action' => 'follow']);
    }
    
    /**
     * 取消关注
     * @param int $instagramUserId
     * @return array
     */
    public function unfollow($instagramUserId)
    {
        return $this->api('v1/users/' . $instagramUserId . '/relationship', 'POST', ['action' => 'unfollow']);
    }
    
    /**
     * 获取最近media
     * @param string $id 取值 'self'字符串 或 instagram user id
     * @return array
     */
    public function getMediaRecent($id)
    {
        return $this->api('v1/users/' . $id . '/media/recent/', 'GET');
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