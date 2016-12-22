<?php

namespace leap\oauth;

use yii\authclient\OAuth2;
use yii\web\HttpException;

class Weibo extends OAuth2
{
    public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    
    public $apiBaseUrl = 'https://api.weibo.com';
    
    /**
     *
     * @return []
     * @see http://open.weibo.com/wiki/Oauth2/get_token_info
     * @see http://open.weibo.com/wiki/2/users/show
     */
    protected function initUserAttributes()
    { 
        return $this->api('oauth2/get_token_info', 'POST');
    }
    
    /**
     * get UserInfo
     * @return []
     * @see http://open.weibo.com/wiki/2/users/show
     */
    public function getUserInfo()
    {
        $userAttributes = $this->getUserAttributes();
        return $this->api("2/users/show.json", 'GET', ['uid' => $userAttributes['uid']]);
    }
    
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['access_token'] = $accessToken->getToken();
        $request->setData($data);
    }
    
    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'weibo';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Weibo';
    }
}