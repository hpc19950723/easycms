<?php

namespace leap\oauth;

use yii\authclient\OAuth2;
use yii\web\HttpException;

class Qq extends OAuth2
{
    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    
    public $apiBaseUrl = 'https://graph.qq.com';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = 'get_user_info';
        }
    }
    
//    public function fetchAccessToken($authCode, array $params = [])
//    {
//        $defaultParams = [
//            'appid' => $this->clientId,
//            'secret' => $this->clientSecret,
//            'code' => $authCode,
//            'grant_type' => 'authorization_code',
//        ];
//
//        $request = $this->createRequest()
//            ->setMethod('POST')
//            ->setUrl($this->tokenUrl)
//            ->setData(array_merge($defaultParams, $params));
//
//        $response = $this->sendRequest($request);
//
//        $token = $this->createToken(['params' => $response]);
//        $this->setAccessToken($token);
//
//        return $token;
//    }
    
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['access_token'] = $accessToken->getToken();
        $data['openid'] = $accessToken->getParam('openid');
        $request->setData($data);
    }
    
    protected function initUserAttributes()
    {
        return $this->api('oauth2.0/me', 'GET');
    }
    
    public function getUserInfo()
    {
        return $this->api("user/get_user_info", 'GET', [
            'oauth_consumer_key' => $this->clientId,
        ]);
    }
    
    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'qq';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'QQ';
    }
}