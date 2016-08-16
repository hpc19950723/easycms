<?php

namespace common\modules\admin\controllers;

use Yii;
use yii\caching\TagDependency;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use Exception;

/**
 * Description of RuleController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RouteController extends \common\modules\admin\components\BaseController
{
    const CACHE_TAG = 'izyue.admin.route';

    /**
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', ['routes' => $this->getRoutes()]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->getResponse()->format = 'json';
        $routes = Yii::$app->getRequest()->post('route', '');
        $routes = preg_split('/\s*,\s*/', trim($routes), -1, PREG_SPLIT_NO_EMPTY);
        $this->saveNew($routes);
        //Helper::invalidate();
        return $this->getRoutes();
    }

    /**
     * Assign or remove items
     * @param string $action
     * @return array
     */
    public function actionAssign()
    {
        $post = Yii::$app->getRequest()->post();
        $action = $post['action'];
        $routes = $post['routes'];
        $manager = Yii::$app->getAuthManager();
        $error = [];
        if ($action == 'assign') {
            $this->saveNew($routes);
        } else {
            foreach ($routes as $route) {
                $child = $manager->getPermission($route);
                try {
                    $manager->remove($child);
                } catch (Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        //Helper::invalidate();
        Yii::$app->getResponse()->format = 'json';
        return array_merge($this->getRoutes(), ['errors' => $error]);
    }

    /**
     * Refresh cache
     * @return type
     */
    public function actionRefresh()
    {
        $this->invalidate();
        Yii::$app->getResponse()->format = 'json';
        return $this->getRoutes();
    }

    /**
     * Get avaliable and assigned routes
     * @return array
     */
    protected function getRoutes()
    {
        $manager = Yii::$app->getAuthManager(); 
        $routes = array_flip($this->getAppRoutes());
        $exists = [];
        foreach (array_keys($manager->getPermissions()) as $name) {
            if ($name[0] !== '/') {
                continue;
            }
            $exists[] = $name;
            unset($routes[$name]);
        }
        return[
            'avaliable' => array_keys($routes),
            'assigned' => $exists
        ];
    }

    /**
     * Save one or more route(s)
     * @param array $routes
     */
    private function saveNew($routes)
    {
        $manager = Yii::$app->getAuthManager();
        foreach ($routes as $route) {
            try {
                $r = explode('&', $route);
                $item = $manager->createPermission('/' . trim($route, '/'));
                if (count($r) > 1) {
                    $action = '/' . trim($r[0], '/');
                    if (($itemAction = $manager->getPermission($action)) === null) {
                        $itemAction = $manager->createPermission($action);
                        $manager->add($itemAction);
                    }
                    unset($r[0]);
                    foreach ($r as $part) {
                        $part = explode('=', $part);
                        $item->data['params'][$part[0]] = isset($part[1]) ? $part[1] : '';
                    }
                    $this->setDefaultRule();
                    $item->ruleName = RouteRule::RULE_NAME;
                    $manager->add($item);
                    $manager->addChild($item, $itemAction);
                } else {
                    $manager->add($item);
                }
            } catch (Exception $e) {

            }
        }
    }

    /**
     * Get list of application routes
     * @return array
     */
    public function getAppRoutes()
    {
        $key = __METHOD__;
//        $cache = Configs::instance()->cache;
        $cache = null;
        if ($cache === null || ($result = $cache->get($key)) === false) {
            $result = [];
            $this->getRouteRecrusive(Yii::$app, $result);
            if ($cache !== null) {
                $cache->set($key, $result, Configs::instance()->cacheDuration, new TagDependency([
                    'tags' => self::CACHE_TAG
                ]));
            }
        }

        return $result;
    }

    /**
     * Get route(s) recrusive
     * @param \yii\base\Module $module
     * @param array $result
     */
    private function getRouteRecrusive($module, &$result)
    {
        $token = "Get Route of '" . get_class($module) . "' with id '" . $module->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            foreach ($module->getModules() as $id => $child) {
                if (($child = $module->getModule($id)) !== null) {
                    $this->getRouteRecrusive($child, $result);
                }
            }

            foreach ($module->controllerMap as $id => $type) {
                $this->getControllerActions($type, $id, $module, $result);
            }

            $namespace = trim($module->controllerNamespace, '\\') . '\\';
            $this->getControllerFiles($module, $namespace, '', $result);
            $result[] = ($module->uniqueId === '' ? '' : '/' . $module->uniqueId) . '/*';
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list controller under module
     * @param \yii\base\Module $module
     * @param string $namespace
     * @param string $prefix
     * @param mixed $result
     * @return mixed
     */
    private function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = Yii::getAlias('@' . str_replace('\\', '/', $namespace), false);
        $token = "Get controllers from '$path'";
        Yii::beginProfile($token, __METHOD__);
        try {
            if (!is_dir($path)) {
                return;
            }
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($path . '/' . $file)) {
                    $this->getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                    $id = Inflector::camel2id(substr(basename($file), 0, -14));
                    $className = $namespace . Inflector::id2camel($id) . 'Controller';
                    if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                        $this->getControllerActions($className, $prefix . $id, $module, $result);
                    }
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list action of controller
     * @param mixed $type
     * @param string $id
     * @param \yii\base\Module $module
     * @param string $result
     */
    private function getControllerActions($type, $id, $module, &$result)
    {
        $token = "Create controller with cofig=" . VarDumper::dumpAsString($type) . " and id='$id'";
        Yii::beginProfile($token, __METHOD__);
        try {
            /* @var $controller \yii\base\Controller */
            $controller = Yii::createObject($type, [$id, $module]);
            $this->getActionRoutes($controller, $result);
            $result[] = '/' . $controller->uniqueId . '/*';
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get route of action
     * @param \yii\base\Controller $controller
     * @param array $result all controller action.
     */
    private function getActionRoutes($controller, &$result)
    {
        $token = "Get actions of controller '" . $controller->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $prefix = '/' . $controller->uniqueId . '/';
            foreach ($controller->actions() as $id => $value) {
                $result[] = $prefix . $id;
            }
            $class = new \ReflectionClass($controller);
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                    $result[] = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Ivalidate cache
     */
    protected function invalidate()
    {
        if (Configs::instance()->cache !== null) {
            TagDependency::invalidate(Configs::instance()->cache, self::CACHE_TAG);
        }
    }

    /**
     * Set default rule of parameterize route.
     */
    protected function setDefaultRule()
    {
        if (Yii::$app->authManager->getRule(RouteRule::RULE_NAME) === null) {
            Yii::$app->authManager->add(Yii::createObject([
                    'class' => RouteRule::className(),
                    'name' => RouteRule::RULE_NAME]
            ));
        }
    }
}
