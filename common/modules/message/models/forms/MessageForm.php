<?php

namespace common\modules\message\models\forms;

use Yii;
use yii\web\NotFoundHttpException;
use common\modules\message\models\Message;
use common\modules\core\components\ImageUploader;
use common\modules\core\components\Tools;

class MessageForm extends \yii\base\Model
{
    public $sender_id = 0;
    
    public $receiver_id;
    
    public $title;
    
    public $content;
    
    public $image;
    
    public $type;
    
    private $_message;
    
    const SCENARIOS_BACKEND_CREATE = 'backend_create';              //后端创建
    
    const SCENARIOS_BACKEND_UPDATE = 'backend_update';              //后端更新

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'title', 'content'], 'required'],
            [['sender_id', 'receiver_id'], 'integer'],
            ['type', 'in', 'range' => array_keys(Message::getTypes())],
            ['title', 'string', 'max' => 50],
            ['image','image', 'extensions' => 'png, jpg, jpeg', 'mimeTypes' => 'image/jpeg, image/png', 'checkExtensionByMimeType' => false],
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '发送者ID',
            'receiver_id' => '接收者ID',
            'title' => '标题',
            'content' => '消息主体',
            'type' => '消息类型',
            'image' => '图片'
        ];
    }
    
    
    /**
     * 获取当前是否为新记录
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_message === null;
    }
    
    
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_BACKEND_CREATE => ['content', 'title', 'image', 'receiver_id', 'sender_id', 'type'],
            self::SCENARIOS_BACKEND_UPDATE => ['content', 'title', 'image', 'receiver_id', 'sender_id', 'type'],
         ];
        return array_merge( parent:: scenarios(), $scenarios);
    }
    
    
    public function initData($messageId)
    {
        $this->_message = Message::findOne($messageId);
        if($this->_message === null) {
            throw new NotFoundHttpException('页面不存在');
        } else {
            $this->sender_id = $this->_message->sender_id;
            $this->receiver_id = $this->_message->receiver_id;
            $this->title = $this->_message->title;
            $this->content = $this->_message->content;
            $this->type = $this->_message->type;
            $this->image = $this->_message->image;
        }
    }
    
    
    public function save()
    {
        if($this->validate()) {
            if($this->isNewRecord) {
                $this->_message = new Message();
            }
            $fileUploader = new ImageUploader([
                'uploadedFile' => $this->image,
                'uploadedFileDir' => Tools::getUploadDir('message'),
                'uploadedFilePrefix' => 'MSG',
                'oldFilePath' => $this->_message->image?:null
            ]);
            $imageName = $fileUploader->save();
            if($imageName) {
                $this->_message->image = Tools::getUploadDir('message') . '/' . $imageName;
            }
            $this->_message->receiver_id = 0;
            $this->_message->sender_id = 0;
            $this->_message->title = $this->title;
            $this->_message->content = $this->content;
            $this->_message->type = $this->type;
            return $this->_message->save();
        } else {
            return false;
        }
    }
}
