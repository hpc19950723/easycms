<?php
namespace common\modules\admin;

use common\modules\module\models\Module;

class BaseModule extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $this->viewPath = substr($this->basePath, 0, stripos($this->basePath, 'modules') + 7) . DIRECTORY_SEPARATOR . 'views' . substr($this->basePath, stripos($this->basePath, 'modules') + 7);
        $this->viewPath = preg_replace('/views\/(\w+)\/admin/', 'views/$1', $this->viewPath);
        $this->setLayoutPath('@themes/backend/base/views/layouts');
        
        if($this->id !== 'admin') {
            $modules = Module::getAll();
            if(!isset($modules[$this->id]['status'])
                || $modules[$this->id]['status'] == Module::STATUS_INACTIVE
                || $modules[$this->id]['enabled_admin'] == Module::VALUE_NO) {
                throw new \yii\web\NotFoundHttpException('该模块已经关闭');
            }
        }
    }
}