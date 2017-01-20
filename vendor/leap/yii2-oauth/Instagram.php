<?php

namespace leap\oauth;

use Yii;
use yii\authclient\OAuth2;
use yii\web\HttpException;
use yii\authclient\InvalidResponseException;

class Instagram extends OAuth2
{
    public $authUrl = 'https://api.instagram.com/oauth/authorize';
    
    public $tokenUrl = 'https://api.instagram.com/oauth/access_token';
    
    public $apiBaseUrl = 'https://api.instagram.com';
    
    public $validateAuthState = false;
    
    public function api($apiSubUrl, $method = 'GET', $data = [], $headers = [])
    {
        try{
            return parent::api($apiSubUrl, $method, $data, $headers);
        } catch (InvalidResponseException $e) {
            $data = $e->response->getData();
            if(isset($data['meta']['error_type']) && $data['meta']['error_type'] == 'OAuthAccessTokenException') {
                throw new HttpException(200, 'Instagram access_token provided is invalid.', 400);
            } else {
                throw $e;
            }
        }
    }
    
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
     * 获取去其他用户的关系
     * @param string $id
     * @return array
     */
    public function getRelationship($id)
    {
        return $this->api('v1/users/' . $id . '/relationship/', 'GET');
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