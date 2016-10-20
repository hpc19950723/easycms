<?php

namespace common\modules\core\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class FileUploader extends Component
{
    /**
     * yii\web\UploadedFile类
     */
    public $uploadedFile;
    
    /**
     * 上传文件根目录
     * @var type 
     */
    public $uploadedFileRoot = '@uploads';
    
    /**
     * 文件上传路径
     */
    public $uploadedFileDir;
    
    /**
     * 文件名称前缀
     */
    public $uploadedFilePrefix;
    
    /**
     * 保存文件名
     * @var string
     */
    protected $_uploadedFileName;
    
    /**
     * 原文件路径
     */
    public $oldFilePath;
    
    
    /**
     * 保存图片到指定目录
     * @return boolean
     */
    public function save()
    {
        $uploadSuccessful = false;
        if($this->uploadedFile instanceof UploadedFile) {
            $uploadedFileDir = Yii::getAlias($this->uploadedFileRoot . $this->uploadedFileDir);
            FileHelper::createDirectory($uploadedFileDir, 0755, true);
            $uploadSuccessful = $this->uploadedFile->saveAs($uploadedFileDir . '/' . $this->uploadedFileName);
            
            if($uploadSuccessful && $this->oldFilePath) {
                @unlink(Yii::getAlias($this->uploadedFileRoot . $this->oldFilePath));
            }
        }
        if($uploadSuccessful) {
            return $this->uploadedFileName;
        } else {
            return $uploadSuccessful;
        }
    }
    
    
    /**
     * 获取保存的文件名
     * @return string
     */
    public function getUploadedFileName()
    {
        if(!$this->_uploadedFileName) {
            $this->_uploadedFileName = uniqid($this->uploadedFilePrefix) . '.' . $this->uploadedFile->extension;
        }
        return $this->_uploadedFileName;
    }
    
    
    /**
     * 手动设置上传文件名
     */
    public function setUploadedFileName($fileName)
    {
        $this->_uploadedFileName = $fileName;
    }
}