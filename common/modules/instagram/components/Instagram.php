<?php

namespace common\modules\instagram\components;

use Yii;
use yii\base\Component;

class Instagram extends Component
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
    
    public function getCache($method, $params, $readCache = true, $duration = 0, $callback = null)
    {
        $cacheKey = $method . '_' . md5(http_build_query($params) . $this->accessToken);
        $data = Yii::$app->cache->get($cacheKey);
        if(!$readCache || empty($data)) {
            if(is_array($params)) {
                $data = call_user_func_array(array($this->instagram, $method), $params);
            } else {
                $data = call_user_func(array($this->instagram, $method), $params);
            }
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