<?php

namespace common\modules\admin\controllers;

use Yii;
use common\modules\admin\components\BaseController;

class ConfigController extends BaseController
{
    public function createAction($id)
    {
        if ($id === '') {
            $id = $this->defaultAction;
        }
        
        $actionMap = [
            $id => [
                'class' => 'common\modules\admin\controllers\actions\configAction'
            ]
        ];
        return Yii::createObject($actionMap[$id], [$id, $this]);
    }
}