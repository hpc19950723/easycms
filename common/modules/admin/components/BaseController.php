<?php

namespace common\modules\admin\components;

use Yii;
use yii\web\Controller;
use common\modules\admin\models\AdminMenu;
use yii\filters\AccessControl;
use common\modules\admin\models\Admin;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

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
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if(Admin::USER_TYPE_SUPERADMIN == Yii::$app->user->identity->user_type) {
                                return true;
                            } else {
                                $actionId = $action->getUniqueId();
                                $user = Yii::$app->user;
                                if ($user->can('/' . $actionId)) {
                                    return true;
                                }
                                $obj = $action->controller;
                                do {
                                    if ($user->can('/' . ltrim($obj->getUniqueId() . '/*', '/'))) {
                                        return true;
                                    }
                                    $obj = $obj->module;
                                } while ($obj !== null);
                            }
                            return false;
                        }
                    ],

                ],
            ],
        ];
    }
    
    
    public function getMenus()
    {
        $env = Yii::$app->getSession()->get('env', AdminMenu::TYPE_ADMIN);
        $menus = AdminMenu::find()->select(['menu_id','name','route','icon','children_count','child_route'])->where(['parent_id' => 0, 'env' => $env])->orderBy(['position' => SORT_ASC])->createCommand()->queryAll();
        foreach($menus as $key => &$menu) {
            if(0 == $menu['children_count']) {
                if($this->isAccess($menu['route'])) {
                    $menu['child_route'] = explode(',',$menu['child_route']);
                    $menu['child_route'][] = $menu['route'];
                } else {
                    unset($menus[$key]);
                }
                continue;
            }
            $menu['children'] = AdminMenu::find()->select(['name','route','icon','children_count','child_route'])->where(['parent_id' => $menu['menu_id'], 'env' => $env])->orderBy(['position' => SORT_ASC])->createCommand()->queryAll();

            $childRoutes = [];
            foreach($menu['children'] as $childrenKey => $menuChild) {
                if($this->isAccess($menu['children'][$childrenKey]['route'])) {
                    $menu['children'][$childrenKey]['child_route'] = explode(',', $menuChild['child_route']);
                    $menu['children'][$childrenKey]['child_route'][] = $menu['children'][$childrenKey]['route'];
                    $childRoutes = ArrayHelper::merge($childRoutes, $menu['children'][$childrenKey]['child_route']);
                } else {
                    unset($menu['children'][$childrenKey]);
                    continue;
                }
            }
            $menu['child_route'] = $childRoutes;
            if($menu['children_count'] > 0 && count($menu['children']) === 0) {
                unset($menus[$key]);
            }
        }

        return $menus;
    }
    
    
    public function isAccess($permissionName) {
        if (Admin::USER_TYPE_SUPERADMIN == Yii::$app->user->identity->user_type) {
            return true;
        } else {
            $user = Yii::$app->user;
            do {
                if ($user->can($permissionName)) {
                    return true;
                }
                $permissionName = rtrim($permissionName, '/*');
                $permissionName = explode('/', $permissionName);
                array_pop($permissionName);
                $permissionName = '/' . trim(implode('/', $permissionName), '/') . '/*';
            } while ($permissionName !== '//*');
            return false;
        }
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