<?php

namespace common\modules\admin\models\forms;

use yii\base\Model;

class CoreConfigForm extends Model
{
    public $rules;
    
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    
    public function rules()
    {
        return $this->rules;
    }
}