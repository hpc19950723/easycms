<?php
/**
 * 发送短信组件
 */

namespace common\modules\core\components;

use yii\base\Component;
use common\modules\core\components\Tools;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * $sms = new Sms([
 *    'type' => 'register',
 *    'smParams' => [
 *        '132302'
 *    ],
 *    'mobile' => 'xxxxxxxx'
 * ]);
 */
class Sms extends Component
{
    //短信类型
    public $type;
    
    //短信参数
    public $smParams = [];
    
    //手机号
    public $mobile;
    
    /**
     * 获取短信
     * @return string
     */
    public function getMessage()
    {
        return vsprintf($this->_getMessages()[$this->type], $this->smParams);
    }
    
    
    /**
     * 发送短信
     */
    public function send()
    {
        try {
            $params = [
                'mobilePhone' => $this->mobile,
                'msg' => $this->getMessage()
            ];
            $headers = [
                'Content-Type' => 'application/json',
                'X-ML-AppId' => Tools::getModuleParams('core', ['sms', 'account', 'appId']),
                'X-ML-Session-Token' => Tools::getModuleParams('core', ['sms', 'account', 'sessionToken']),
            ];
            $client = new Client();
            $client->request('POST', Tools::getModuleParams('core', ['sms', 'url']), [
                'headers' => $headers,
                'body' => json_encode($params)
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
        return Tools::getModuleParams('core', ['sms', 'content']);
    }
}