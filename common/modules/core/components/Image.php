<?php
namespace common\modules\core\components;

use Yii;
use common\modules\core\components\image\ImageAdapter;
use yii\helpers\Url;
use yii\base\Exception;

class Image
{
    protected $_width;
    
    protected $_height;
    
    protected $_quality = 90;
    
    protected $_watermark;
    
    protected $_watermarkPosition;
    
    protected $_watermarkSize;
    
    protected $_watermarkImageOpacity;
    
    protected $_keepAspectRatio  = true;
    
    protected $_keepFrame        = true;
    
    protected $_keepTransparency = true;
    
    protected $_constrainOnly    = true;
    
    protected $_backgroundColor  = array(255, 255, 255);

    protected $_imageName;
    
    protected $_imageType;
    
    protected $_imageFile;
    
    protected $_destinationImageFile;

    protected $_placeholderName = 'placeholder.jpg';

    protected $_placeholderType = 'placeholder';

    protected $_placeholder;
    
    protected $_adapter;


    protected function _reset()
    {
        $this->_watermark = null;
        $this->_watermarkPosition = null;
        $this->_watermarkSize = null;
        $this->_watermarkImageOpacity = null;
        $this->_imageFile = null;
        $this->_imageName = null;
        $this->_imageType = null;
        $this->_destinationImageFile = null;
        return $this;
    }
    
    
    protected function _getAdapter($adapter = ImageAdapter::ADAPTER_GD2)
    {
        if( !isset($this->_adapter) ) {
            $this->_adapter = ImageAdapter::factory( $adapter );
        }
        return $this->_adapter;
    }


    public function init($imageName, $imageType)
    {
        $this->_reset();
        $this->_imageName = $imageName;
        $this->_imageType = $imageType;
        if(!file_exists($this->getImageFile())) {
            $this->_imageName = $this->_placeholderName;
            $this->_imageType = $this->_placeholderType;
            $this->_imageFile = null;
        }
        return $this;
    }
    
    
    public function setKeepFrame($keep)
    {
        $this->_keepFrame = (bool)$keep;
        return $this;
    }
    
    
    public function resize($width, $height = null)
    {
        $this->setWidth($width);
        $this->setHeight($height);
        return $this;
    }
    
    
    public function open()
    {
        $this->_getAdapter()->checkDependencies();
        $this->_getAdapter()->open($this->getImageFile());
    }
    
    
    public function setWidth($width)
    {
        $this->_width = $width;
    }
    
    
    public function setHeight($height)
    {
        $this->_height = $height;
    }
    
    
    /**
     * 获取图片路径
     * @return string
     */
    protected function getImageFile()
    {
        if(!$this->_imageFile) {
            $this->_imageFile = Yii::getAlias('@uploads/' . $this->_imageType . '/' . $this->_imageName);
        }

        return $this->_imageFile;
    }
    
    
    protected function getDestinationImageFile()
    {
        if(!$this->_destinationImageFile && $this->_width) {
            $this->_destinationImageFile = Yii::getAlias('@uploads/cache/' . $this->_imageType . '/' . $this->_width . 'x' . $this->_height . '/' . $this->_imageName);
        } elseif(!$this->_width) {
            $this->_destinationImageFile = $this->getImageFile();
        }
        
        return $this->_destinationImageFile;
    }
    
    
    public function getUrl()
    {
        return Url::to('@resDomain/cache/' . $this->_imageType . '/' . $this->_width . 'x' . $this->_height . '/' . $this->_imageName);
    }
    

    public function isCached()
    {
        return file_exists($this->getDestinationImageFile());
    }
    
    
    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $this->_placeholder = Url::to('@resDomain/' . $this->_imageType . '/' . $this->_imageName);
        }
        return $this->_placeholder;
    }
    
    
    /**
     * Return Image URL
     *
     * @return string
     */
    public function __toString()
    {
        try {
            if ($this->isCached()) {
                return $this->getUrl();
            } else {
                $this->open();
                $this->_getAdapter()->keepFrame($this->_keepFrame);
                $this->_getAdapter()->keepAspectRatio($this->_keepAspectRatio);
                $this->_getAdapter()->keepTransparency($this->_keepTransparency);
                $this->_getAdapter()->constrainOnly($this->_constrainOnly);
                $this->_getAdapter()->backgroundColor($this->_backgroundColor);
                $this->_getAdapter()->resize($this->_width, $this->_height);
                $this->_getAdapter()->save($this->getDestinationImageFile());
                return $this->getUrl();
            }
        } catch (Exception $e) {
            $url = $this->getPlaceholder();
        }
        return $url;
    }
}