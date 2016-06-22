<?php

namespace common\modules\admin\models\forms;

class ModuleForm extends \yii\base\Model
{
    public $name;
    
    public $title;
    
    public $class;
    
    public $settings;
    
    public $version;
    
    public $status;
    
    
    public function rules()
    {
        return [
            [['name', 'title', 'version']],
            [['settings'], 'string'],
            [['status'], 'integer'],
            [['name', 'title', 'version'], 'string', 'max' => 45],
            [['class'], 'string', 'max' => 255],
        ];
    }
}