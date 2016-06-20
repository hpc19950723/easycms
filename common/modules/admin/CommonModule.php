<?php
namespace common\modules\admin;

class CommonModule extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $this->viewPath = substr($this->basePath, 0, stripos($this->basePath, 'modules') + 7) . DIRECTORY_SEPARATOR . 'views' . substr($this->basePath, stripos($this->basePath, 'modules') + 7);
        $this->viewPath = preg_replace('/views\/(\w+)\/admin/', 'views/$1', $this->viewPath);
        $this->setLayoutPath('@themes/backend/base/views/layouts');
    }
}