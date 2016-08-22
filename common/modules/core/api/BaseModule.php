<?php
namespace common\modules\core\api;

use common\modules\module\models\Module;

class BaseModule extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        if($this->id !== 'core') {
            $modules = Module::getAll();
            if(!isset($modules[$this->id]['status'])
                || $modules[$this->id]['status'] == Module::STATUS_INACTIVE
                || $modules[$this->id]['has_api'] == Module::VALUE_NO) {
                throw new \yii\web\NotFoundHttpException('该模块已经关闭');
            }
        }
    }
}