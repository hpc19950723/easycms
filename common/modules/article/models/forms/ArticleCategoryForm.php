<?php

namespace common\modules\article\models\forms;

use yii\web\NotFoundHttpException;
use common\modules\article\models\ArticleCategory;

class ArticleCategoryForm extends \yii\base\Model
{
    public $identifier;
    
    public $parent_id = 0;
    
    public $name;
    
    public $description;
    
    public $position = 0;
    
    public $status = ArticleCategory::STATUS_ACTIVE;
    
    private $_articleCategory;
    
    private $_categoryId;
    
    const SCENARIOS_CREATE = 'create';
    
    const SCENARIOS_UPDATE = 'update';
    
    public function rules()
    {
        return [
            [['name', 'identifier', 'status', 'parent_id'], 'required'],
            ['identifier', 'unique', 'targetClass' => '\common\modules\article\models\ArticleCategory', 'targetAttribute' => 'identifier', 'when' => function(){
                return $this->isNewRecord || $this->_articleCategory->identifier != $this->identifier;
            }, 'filter' => ['status' => [ArticleCategory::STATUS_INACTIVE, ArticleCategory::STATUS_ACTIVE]]],
            [['name', 'identifier'], 'string', 'max' => 20],
            ['status', 'in', 'range' => [ArticleCategory::STATUS_INACTIVE, ArticleCategory::STATUS_ACTIVE]],
            ['description', 'safe'],
            ['position', 'integer', 'min' => 0, 'max' => 32767],
        ];
    }
    
    
    public function scenarios() {
        $scenarios = [
            self::SCENARIOS_CREATE => ['name', 'identifier', 'parent_id', 'description', 'position', 'status'],
            self::SCENARIOS_UPDATE => ['name', 'identifier', 'parent_id', 'description', 'position', 'status'],
         ];
        return array_merge( parent:: scenarios(), $scenarios);
    }
    
    
    /**
     * 获取是否为新记录
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_articleCategory === null;
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'identifier'=> '标识符',
            'parent_id' => '主分类',
            'description' => '描述',
            'status' => '状态',
            'position' => '排序'
        ];
    }
    
    
    public function initData($categoryId)
    {
        $this->_articleCategory = ArticleCategory::findOne($categoryId);
        if ($this->_articleCategory === null) {
            throw new NotFoundHttpException('页面不存在');
        }
        
        $this->attributes = $this->_articleCategory->attributes;
    }
    
    
    public function save()
    {
        if ($this->validate()) {
            if ($this->isNewRecord) {
                $this->_articleCategory = new ArticleCategory();
            }
            $this->_articleCategory->setAttributes($this->attributes, false);
            
            return $this->_articleCategory->save();
        } else {
            return false;
        }
    }
}