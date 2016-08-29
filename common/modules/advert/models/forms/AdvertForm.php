<?php

namespace common\modules\advert\models\forms;

use common\modules\advert\models\Advert;
use yii\web\NotFoundHttpException;
use common\modules\core\components\ImageUploader;
use common\modules\core\components\Tools;

class AdvertForm extends \yii\base\Model
{
    public $name;
    
    public $position_id;
    
    public $link;
    
    public $image;
    
    public $start_time;
    
    public $end_time;
    
    public $position;
    
    public $status = Advert::STATUS_ACTIVE;
    
    private $_advert;
    
    const SCENARIOS_CREATE = 'create';
    
    const SCENARIOS_UPDATE = 'update';
    
    public function rules()
    {
        return [
            [['name', 'position_id', 'status'], 'required'],
            ['image', 'required', 'on' => [self::SCENARIOS_CREATE]],
            [['name'], 'string', 'max' => 20],
            ['link', 'string', 'max' => 255],
            ['status', 'in', 'range' => [Advert::STATUS_INACTIVE, Advert::STATUS_ACTIVE]],
            [['start_time', 'end_time'], 'date', 'format' => 'yyyy-mm-dd'],
            ['end_time', 'compare', 'compareAttribute' => 'start_time', 'operator' => '>=', 'message' => '广告下线时间不能小于广告上线时间'],
            ['image', 'image', 'extensions' => 'jpg, png', 'maxSize' => 2097152, 'mimeTypes' => 'image/jpeg, image/png', 'checkExtensionByMimeType' => false, 'tooBig' => '文件"{file}"太大, 它的大小不能超过2.00 MB'],
            ['position', 'integer', 'min' => 0, 'max' => 32767],
        ];
    }
    
    
    public function scenarios() {
        $scenarios = [
            self::SCENARIOS_CREATE => ['name', 'position_id', 'link', 'image', 'start_time', 'end_time', 'position', 'status'],
            self::SCENARIOS_UPDATE => ['name', 'position_id', 'link', 'image', 'start_time', 'end_time', 'position', 'status'],
         ];
        return array_merge( parent:: scenarios(), $scenarios);
    }
    
    
    /**
     * 获取是否为新记录
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_advert === null;
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'position_id'=> '广告位',
            'image' => '图片',
            'link' => '链接',
            'start_time' => '上线时间',
            'end_time' => '下线时间',
            'status' => '状态',
            'position' => '排序'
        ];
    }
    
    
    public function initData($advertId)
    {
        $this->_advert = Advert::findOne($advertId);
        if ($this->_advert === null) {
            throw new NotFoundHttpException('页面不存在');
        }
        
        $this->attributes = $this->_advert->attributes;
        if (!empty($this->_advert->start_time)) {
            $this->start_time = explode(' ',$this->_advert->start_time)[0];
        } else {
            $this->start_time = '';
        }
        if (!empty($this->_advert->end_time)) {
            $this->end_time = explode(' ',$this->_advert->end_time)[0];
        } else {
            $this->end_time = '';
        }
    }
    
    
    public function save()
    {
        if ($this->validate()) {
            if ($this->isNewRecord) {
                $this->_advert = new Advert();
            }
            $fileUploader = new ImageUploader([
                'uploadedFile' => $this->image,
                'uploadedFileDir' => Tools::getUploadDir('advert'),
                'uploadedFilePrefix' => 'ADVT',
                'oldFilePath' => $this->_advert->image?:null
            ]);
            $imageName = $fileUploader->save();
            if($imageName) {
                $this->_advert->image = Tools::getUploadDir('advert') . '/' . $imageName;
            }
            $this->_advert->name = $this->name;
            $this->_advert->position = $this->position;
            $this->_advert->status = $this->status;
            $this->_advert->start_time = $this->start_time;
            $this->_advert->end_time = $this->end_time;
            $this->_advert->position_id = $this->position_id;
            $this->_advert->link = $this->link;
            
            return $this->_advert->save();
        } else {
            return false;
        }
    }
}