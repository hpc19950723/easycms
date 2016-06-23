<?php

namespace common\modules;

use common\modules\admin\models\Module;

class AdminModule extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        
        $adminModuelName = 'adm';
        $models = Module::find()->where(['has_admin' => Module::VALUE_YES])
                ->orWhere(['name' => $adminModuelName])
                ->andWhere(['status' => Module::STATUS_ACTIVE])
                ->all();

        $modules = [];
        foreach($models as $model) {
            $modules[$model->name] = [
                'class' => $model->name === $adminModuelName ? $model->dir . '\Module' : $model->dir . '\admin\Module',
                'defaultRoute' => 'index'
            ];
        }
        $this->setModules($modules);
    }
}