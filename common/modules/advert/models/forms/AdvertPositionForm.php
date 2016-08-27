<?php

namespace common\modules\advert\models\forms;

use common\modules\advert\models\AdvertPosition;
use yii\web\NotFoundHttpException;

class AdvertPositionForm extends \yii\base\Model
{
    public $name;
    
    public $identifier;
    
    public $width = 0;
    
    public $height = 0;
    
    public $description;
    
    public $status = AdvertPosition::STATUS_ACTIVE;
    
    private $_advertPosition;
    
    public function rules()
    {
        return [
            [['name', 'identifier', 'status'], 'required'],
            [['name', 'identifier'], 'string', 'max' => 20],
            ['identifier', 'unique', 'targetClass' => '\common\modules\advert\models\AdvertPosition', 'targetAttribute' => 'identifier', 'when' => function(){
                return $this->isNewRecord || $this->_advertPosition->identifier != $this->identifier;
            }],
            ['status', 'in', 'range' => [AdvertPosition::STATUS_INACTIVE, AdvertPosition::STATUS_ACTIVE]],
            [['width', 'height'], 'integer', 'min' => 0],
            ['description', 'string', 'max' => 255],
        ];
    }
    
    
    /**
     * 获取是否为新记录
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_advertPosition === null;
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'identifier' => '标识符',
            'width' => '宽度',
            'height' => '高度',
            'status' => '状态',
            'description' => '描述'
        ];
    }
    
    
    public function initData($positionId)
    {
        $this->_advertPosition = AdvertPosition::findOne($positionId);
        if ($this->_advertPosition === null) {
            throw new NotFoundHttpException('页面不存在');
        }
        
        $this->attributes = $this->_advertPosition->attributes;
    }
    
    
    public function save()
    {
        if ($this->validate()) {
            if ($this->isNewRecord) {
                $this->_advertPosition = new AdvertPosition();
            }
            $this->_advertPosition->setAttributes($this->attributes, false);
            return $this->_advertPosition->save();
        } else {
            return false;
        }
    }
}