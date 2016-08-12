<?php

namespace common\modules\admin\models\forms;

use yii\base\Model;
use common\modules\admin\models\CoreConfig;

class CoreConfigForm extends Model
{
    //path前缀
    private $_pathPrefix;
    
    private $_rules;
    
    private $_attributeLabels;
    
    private $_attributes;
    
    public function __set($name, $value)
    {
        $this->$name = $value;
        $this->_attributes[] = $name;
    }
    
    
    public function setRules($value)
    {
        $this->_rules = $value;
    }
    
    
    public function setAttributeLabels($value)
    {
        $this->_attributeLabels = $value;
    }
    
    
    public function setPathPrefix($value)
    {
        $this->_pathPrefix = $value;
    }
    
    
    public function rules()
    {
        return $this->_rules;
    }
    
    
    public function attributeLabels()
    {
        return $this->_attributeLabels;
    }
    
    
    public function save()
    {
        if($this->validate()) {
            foreach($this->_attributes as $attribute) {
                $path = $this->_pathPrefix . $attribute;
                $model = CoreConfig::find()->where('path=:path', [':path' => $path])->one();
                if($model === null) {
                    $model = new CoreConfig();
                    $model->path = $path;
                }
                $model->value = $this->$attribute;
                $model->save();
            }
            return true;
        } else {
            return false;
        }
    }
}