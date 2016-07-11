<?php

namespace common\modules\admin\models\forms;

use common\modules\admin\models\Module;
use yii\base\Exception;

class ModuleForm extends \yii\base\Model
{
    public $name;
    
    public $title;
    
    public $dir;
    
    public $settings;
    
    public $version;
    
    public $status;
    
    public $has_api;
    
    public $has_admin;
    
    private $_module;


    public function rules()
    {
        return [
            [['name', 'title', 'version', 'has_api', 'has_admin', 'dir', 'status'], 'required'],
            [['settings'], 'string'],
            [['status'], 'in', 'range' => [Module::STATUS_ACTIVE, Module::STATUS_INACTIVE]],
            [['name', 'title', 'version'], 'string', 'max' => 45],
            [['dir'], 'string', 'max' => 255],
        ];
    }
    
    
    /**
     * 获取是否为新记录
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_module === null;
    }
    
    
    /**
     * 属性Label
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => '模块ID',
            'title' => '标题',
            'dir' => '目录',
            'version' => '版本号',
            'has_api' => '存在API子模块',
            'has_admin' => '存在ADMIN子模块',
            'status' => '状态',
        ];
    }
    
    
    /**
     * 保存数据
     * @return boolean
     */
    public function save()
    {
        if($this->validate()) {
            if($this->isNewRecord) {
                $this->_module = new Module();
            }
            $this->_module->name = $this->name;
            $this->_module->title = $this->title;
            $this->_module->dir = $this->dir;
            $this->_module->version = $this->version;
            $this->_module->has_api = $this->has_api;
            $this->_module->has_admin = $this->has_admin;
            $this->_module->status = $this->status;

            if($this->_module->save()) {
                return true;
            } else {
                $this->addErrors($this->_module->getErrors());
                return false;
            }
        } else {
            return false;
        }
    }
}