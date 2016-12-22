<?php

namespace leap\oauth;

use yii\authclient\OAuth2;
use yii\web\HttpException;

class Weixin extends OAuth2
{
    public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
    
    public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    
    public $apiBaseUrl = 'https://api.weixin.qq.com';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = 'snsapi_userinfo';
        }
    }
    
    public function fetchAccessToken($authCode, array $params = [])
    {
        $defaultParams = [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
            'code' => $authCode,
            'grant_type' => 'authorization_code',
        ];

        $request = $this->createRequest()
            ->setMethod('POST')
            ->setUrl($this->tokenUrl)
            ->setData(array_merge($defaultParams, $params));

        $response = $this->sendRequest($request);

        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);

        return $token;
    }
    
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['access_token'] = $accessToken->getToken();
        $data['openid'] = $accessToken->getParam('openid');
        $request->setData($data);
    }
    
    public function initUserAttributes()
    {
        return $this->api('sns/userinfo', 'GET');
    }
    
    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weixin';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Weixin';
    }
}