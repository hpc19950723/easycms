<?php
/**
 * 发送短信组件
 */

namespace common\modules\core\components;

use yii\base\Component;
use GuzzleHttp\Client;
use yii\base\Exception;

/**
 * 发送短信事例
 * $sms = Yii::$app->set([
 *    'type' => 'register',
 *    'smParams' => [
 *        '132302'
 *    ],
 *    'mobile' => 'xxxxxxxx'
 * ]);
 * 
 * $sms->send()
 */
class Sms extends Component
{
    //请求URL
    public $url;
    
    public $appId;
    
    public $sessionToken;
    
    public $content;
    
    //短信类型
    public $type;
    
    //短信参数
    public $smParams = [];
    
    //手机号
    public $mobile;
    
    
    /**
     * 设置短信数据
     * @param type $options
     * @return \common\modules\core\components\Sms
     */
    public function set($options)
    {
        foreach($options as $key => $option) {
            $this->{$key} = $option;
        }
        return $this;
    }
    
    /**
     * 获取短信
     * @return string
     */
    public function getMessage()
    {
        if(isset($this->_getMessages()[$this->type])) {
            return vsprintf($this->_getMessages()[$this->type], $this->smParams);
        } else {
            throw new Exception(sprintf('短信类型%s,未进行配置', $this->type));
        }
    }
    
    
    /**
     * 发送短信
     */
    public function send()
    {
        try {
            $data = [
                'mobilePhone' => $this->mobile,
                'msg' => $this->getMessage()
            ];
            $headers = [
                'Content-Type' => 'application/json',
                'X-ML-AppId' => $this->appId,
                'X-ML-Session-Token' => $this->sessionToken,
            ];
            $client = new Client();
            $client->request('POST', $this->url, [
                'headers' => $headers,
                'body' => json_encode($data)
            ]);
            return true;
        } catch (\yii\base\Exception $e) {
            return false;
        }
    }
    
    
    /**
     * 获取短信配置
     * @return array
     */
    private function _getMessages()
    {
        return $this->content;
    }
}