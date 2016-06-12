<?php

namespace common\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use common\modules\admin\models\AdminMenus;
use yii\filters\AccessControl;
use common\modules\admin\models\Admin;
use yii\helpers\Json;

class BaseController extends Controller
{   
    public function behaviors()
    {
        return [
            //所有控制器需要登录用户才有权访问
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Admin::USER_TYPE_SUPERADMIN == Yii::$app->user->identity->user_type || Yii::$app->user->can($action->controller->id . '/' . $action->id);
                        }
                    ],
                
                ],
            ],
        ];
    }
    
    
    public function getMenus()
    {
        $menus = AdminMenus::find()->select(['menu_id','name','route','icon','children_count'])->where(['parent_id' => 0])->orderBy(['position' => SORT_ASC])->createCommand()->queryAll();
        foreach($menus as &$menu) {
            if(0 == $menu['children_count']) {
                continue;
            }
            
            $menu['children'] = AdminMenus::find()->select(['name','route','icon','children_count'])->where(['parent_id' => $menu['menu_id']])->orderBy(['position' => SORT_ASC])->createCommand()->queryAll();
        }
        return $menus;
    }
    
    
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
        $result = [
            'errcode' => $errcode,
            'errmsg' => $errmsg,
        ];
        
        if($data !== null) {
            $result['data'] = Yii::createObject('yii\rest\Serializer')->serialize($data);
        }
        return Json::encode($result);
    }
}