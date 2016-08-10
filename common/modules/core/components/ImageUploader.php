<?php

namespace common\modules\core\components;

use common\modules\core\components\FileUploader;
use yii\web\UploadedFile;

class ImageUploader extends FileUploader
{
    /**
     * 获取保存的文件名
     * @return string
     */
    public function getUploadedFileName()
    {
        if(!$this->_uploadedFileName) {
            $imageSize = getimagesize($this->uploadedFile->tempName);
            $uploadedFileSuffix = '_' . $imageSize[0] . 'x' . $imageSize[1];
            $this->_uploadedFileName = uniqid($this->uploadedFilePrefix) . $uploadedFileSuffix . '.' . $this->uploadedFile->extension;
        }
        return $this->_uploadedFileName;
    }
}