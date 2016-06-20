<?php
namespace common\modules\user;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $modules = [];
        foreach($this->activeModules as $name => $module){
            $modules[$name]['class'] = $module->class;
            if(is_array($module->settings)){
                $modules[$name]['settings'] = $module->settings;
            }
        }
        $this->setModules($modules);
    }
}