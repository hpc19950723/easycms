<?php

namespace common\modules\core\components;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class Tools
{
    /**
     * 获取随机验证码
     * @return string
     */
    public static function getRandomNumber($count = 6, $type = 'mixed')
    {
        $chars = '1234567890abcdefghijklmnopqrstuvwxyz';
        
        switch($type) {
            case 'number':
                $startIndex = 0;
                $endIndex = 9;
                break;
            case 'letter':
                $startIndex = 10;
                $endIndex = 35;
                break;
            default :
                $startIndex = 0;
                $endIndex = 35;
                break;
        }
        
        $randomNumber = '';
        for($i = 0; $i<$count; $i++) {
            $randomNumber .= substr($chars, rand($startIndex, $endIndex), 1);
        }
        return $randomNumber;
    }
    
    
    /**
     * 获取文件全路径
     * @param type $filename
     * @param type $type
     * @return string
     */
    public static function getFileUrl($filename, $type)
    {
        return $filename?Url::to('@resDomain/' . $type . '/' . $filename):'';
    }
    
    
    /**
     * 判断当前日期是否是可用日期
     * @param type $startData
     * @param type $endData
     */
    public static function isAvailableDate($start, $end)
    {
        $current = date('Y-m-d');
        $start = $start?date('Y-m-d', strtotime($start)):$current;
        $end = $end?date('Y-m-d', strtotime($end)):$current;

        if($start <= $current && $end >= $current) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * 添加get查询数据
     * @param type $values
     */
    public static function addQueryParams($values)
    {
        Yii::$app->request->setQueryParams(\yii\helpers\ArrayHelper::merge(Yii::$app->request->get(), $values));
    }
    
    
    /**
     * 获取post数据, 可附加额外数据
     * @param array $values 附加数据,必须是数组形式
     * @param string $formName 指定数据附加到特定键
     */
    public static function getPost(array $values, $formName = null)
    {
        if(Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if($formName !== null) {
                $data[$formName] = ArrayHelper::merge($data[$formName], $values);
            } else {
                $data = ArrayHelper::merge($data, $values);
            }
            return $data;
        } else {
            return;
        }
    }
    
    
    /**
     * 获取到下个给定时间点还有多长时间,单位 秒
     * @param mixed $time 可以是一个时间字符串,也可以是时间字符串数组
     * 格式为 h:m:s
     * @return $duration 返回值为到下个时间点还有多长时间,以秒为单位
     */
    public static function getDuration($time)
    {   
        if(!is_array($time)) {
            $time = (array)$time;
        }
        
        $seconds = [];
        foreach($time as $value) {
            $timeArray = explode(':', $value);
            if(3 != count($timeArray)) {
                return false;
            }
            list($hour, $minute, $second) = $timeArray;
            if((int)$hour < 0 || (int)$hour > 23 || (int)$minute < 0 || (int)$minute > 59 || (int)$second < 0 || (int)$second > 59) {
                return false;
            }
            $seconds[] = $hour * 3600 + $minute * 60 + $second;
        }
        
        sort($seconds);
        $currentTimeSecond = idate('H') * 3600 + idate('i') * 60 + idate('s');
        foreach($seconds as $second) {
            if(($second - $currentTimeSecond) > 0) {
                return $second - $currentTimeSecond;
            }
        }
        
        return $seconds[0] + (24 * 3600 - $currentTimeSecond);
    }
    
    
    /**
     * 保留小数位数,向下取数
     * @param float $number     //数字
     * @param int $precision    //精度
     * 例如, roundDown(1.20058, 4)   return 1.2005
     */
    public static function roundDown($number, $precision)
    {
        $pow = pow(10, (int)$precision);
        return floor($number*$pow)/$pow;
    }

    /**
     * 判断请示来源
     */
    public static function get_device_type()
    {
        //全部变成小写字母
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $type = 'other';
        //分别进行判断
        if(strpos($agent, 'iphone') || strpos($agent, 'ipad') || strpos($agent, 'ipod'))
        {
            $type = 'ios';
        }

        if(strpos($agent, 'android'))
        {
            $type = 'android';
        }
        return $type;
    }
    
    
    /**
     * 获取一条错误信息
     * @param type $errors
     * @return type
     */
    public static function getFirstError($errors)
    {
        foreach($errors as $error) {
            return $error[0];
        }
        
        return false;
    }
    
    
    /**
     * 格式化数字
     * @param int $num
     * @return string
     */
    public static function formatNumber($num)
    {
        return ($num/100000000 > 1) ? sprintf('%.2f', $num/100000000).'亿' : (($num/10000 > 1) ? sprintf('%.2f', $num/10000).'万' : sprintf('%.2f', $num));
    }
}