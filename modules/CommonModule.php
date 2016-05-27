<?php
namespace app\modules;

class CommonModule extends \yii\base\Module
{
    public function init()
    {
        $this->viewPath = $this->basePath;
        parent::init();
    }
}