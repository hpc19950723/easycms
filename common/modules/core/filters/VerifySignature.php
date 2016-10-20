<?php

namespace common\modules\core\filters;

use Yii;
use yii\web\HttpException;

class VerifySignature extends \yii\base\ActionFilter
{
    public $enabled = true;
    
    public $expirationTime = 300;
    
    public $salt;
    
    public function beforeAction($action)
    {
        if (!$this->enabled) {
            return true;
        }

        return $this->checkParams(Yii::$app->request->get()) &&
            $this->checkParams(Yii::$app->request->post());
    }
    
    
    public function checkParams($data)
    {
        if(empty($data)) {
            return true;
        }
        if(empty($data['did']) || empty($data['timestamp']) || empty($data['sign']) || abs(time() - $data['timestamp']) > $this->expirationTime) {
            throw new HttpException(200, '签名验证失败', 10005);
        }
        
        $oldSign = $data['sign'];
        unset($data['sign']);
        ksort($data);
        $splice = '';
        foreach ($data as $key => $value) {
            if($splice === '') {
                $splice = $key . $value;
            } else {
                $splice .= $key . $value;
            }
        }
        $newSign = strtoupper(md5($splice . $this->salt));

        //如果需要防止重放攻击需要缓存签名并验证
        if($oldSign !== $newSign) {
            throw new HttpException(200, '签名验证失败', 10005);
        } else {
            return true;
        }
    }
}