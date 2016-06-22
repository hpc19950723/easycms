<?php
namespace common\modules\user;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        
        $modules = [
            'admin' => [
                'class' => 'common\modules\user\admin\Module',
                'defaultRoute' => 'index',
            ]
        ];
        $this->setModules($modules);
    }
}