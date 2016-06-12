<?php
namespace common\modules\admin;

class Module extends \common\modules\CommonModule
{
    public $controllerNamespace = 'common\modules\admin\controllers';
    
    public function init()
    {
        $this->viewPath = $this->basePath;
        parent::init();
    }
}
