<?php

namespace common\modules\core\api\components;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    /**
     * 返回成功结果,附加成功code
     * @param type $data
     * @return array
     */
    public static function formatSuccessResult($data = null)
    {
        return self::formatResult(0, 'ok', $data);
    }
    
    
    /**
     * 返回结果,附加部分信息
     * @param int $errcode
     * @param string $errmsg
     * @param array $data
     */
    public static function formatResult($errcode, $errmsg, $data = null)
    {
        $callback = Yii::$app->request->get('callback');
        $result = [
            'errcode' => $errcode,
            'errmsg' => $errmsg,
        ];
        
        if($data !== null) {
            $result['data'] = Yii::createObject('yii\rest\Serializer')->serialize($data);
        }
        
        //jsonp数据格式
        if(!is_null($callback)) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSONP;
            $result = [
                'data' => $result,
                'callback' => $callback,
            ];
        }
        
        return $result;
    }
    
    
    protected function addPaginationHeaders($pagination)
    {
        $links = [];
        foreach ($pagination->getLinks(true) as $rel => $url) {
            $links[] = "<$url>; rel=$rel";
        }

        Yii::$app->getResponse()->getHeaders()
            ->set('X-Pagination-Total-Count', $pagination->totalCount)
            ->set('X-Pagination-Page-Count', $pagination->getPageCount())
            ->set('X-Pagination-Current-Page', $pagination->getPage() + 1)
            ->set('X-Pagination-Per-Page', $pagination->pageSize)
            ->set('Link', implode(', ', $links));
    }
}