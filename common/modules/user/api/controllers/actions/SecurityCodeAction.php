<?php

namespace common\modules\user\api\controllers\actions;

class SecurityCodeAction extends \yii\base\Action
{
    public function run()
    {
        return $this->controller->send(str_replace('-', '_', $this->id));
    }
}