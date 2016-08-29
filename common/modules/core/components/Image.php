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
    
    //目标宽高
    protected $_dstWidth;

    protected $_dstHeight;
    
    //原始图片宽高
    protected $_imageWidth;
    protected $_imageHight;

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

    protected $_dstImageName;
    
    protected $_imageFile;
    
    protected $_destinationImageFile;

    protected $_placeholderName = '/placeholder/placeholder_460x460.jpg';

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


    public function init($imageName)
    {
        $this->_reset();
        $this->_imageName = $imageName;
        if(!file_exists($this->getImageFile())) {
            $this->_imageName = $this->_placeholderName;
            $this->_imageFile = null;
        }
        $this->initImageSrcWidthHeight();
        return $this;
    }
    
    
    public function setKeepFrame($keep)
    {
        $this->_keepFrame = (bool)$keep;
        return $this;
    }
    
    
    public function resize($width = null, $height = null)
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->initDestinationWidthHeight();
        $this->initDstImageName();

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
            $this->_imageFile = Yii::getAlias('@uploads' . $this->_imageName);
        }

        return $this->_imageFile;
    }
    
    
    protected function getDestinationImageFile()
    {
        if(!$this->_destinationImageFile) {
            $this->_destinationImageFile = Yii::getAlias('@uploads/cache/' . $this->_width . 'x' . $this->_height . $this->_dstImageName);
        }

        return $this->_destinationImageFile;
    }
    
    
    public function getUrl()
    {
        return Url::to('@resDomain/cache/' . $this->_width . 'x' . $this->_height . $this->_dstImageName);
    }
    

    public function isCached()
    {
        return file_exists($this->getDestinationImageFile());
    }
    
    
    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $this->_placeholder = Url::to('@resDomain' . $this->_imageName);
        }
        return $this->_placeholder;
    }
    
    
    public function initDestinationWidthHeight()
    {
        if (empty($this->_width) && empty($this->_height)) {
            throw new Exception('Invalid image dimensions.');
        }

        $this->_dstWidth = $this->_width;
        $this->_dstHeight = $this->_height;
        // calculate lacking dimension
        if (!$this->_keepFrame) {
            if (null === $this->_width) {
                $this->_dstWidth = round($this->_height * ($this->_imageWidth / $this->_imageHeight));
            }
            elseif (null === $this->_height) {
                $this->_dstHeight = round($this->_width * ($this->_imageHeight / $this->_imageWidth));
            }
            
            if ($this->_keepAspectRatio) {
                // do not make picture bigger, than it is, if required
                if ($this->_constrainOnly) {
                    if (($this->_dstWidth >= $this->_imageWidth) && ($this->_dstHeight >= $this->_imageHeight)) {
                        $this->_dstWidth  = $this->_imageWidth;
                        $this->_dstHeight = $this->_imageHeight;
                    }
                }
                // keep aspect ratio
                if ($this->_imageWidth / $this->_imageHeight >= $this->_dstWidth / $this->_dstHeight) {
                    $this->_dstHeight = round(($this->_dstWidth / $this->_imageWidth) * $this->_imageHeight);
                } else {
                    $this->_dstWidth = round(($this->_dstHeight / $this->_imageHeight) * $this->_imageWidth);
                }
            }
        }
        else {
            if (null === $this->_width) {
                $this->_dstWidth = $this->_height;
            }
            elseif (null === $this->_height) {
                $this->_dstHeight = $this->_width;
            }
        }
//        echo $this->_dstWidth, ' ', $this->_dstHeight;exit;
    }
    
    
    public function initDstImageName()
    {
        $lastUnderlinePostion = strrpos($this->_imageName, '_');
        $lastPointPosition = strrpos($this->_imageName, '.');
        $this->_dstImageName = substr($this->_imageName, 0, $lastUnderlinePostion + 1) . $this->_dstWidth . 'x' . $this->_dstHeight . substr($this->_imageName, $lastPointPosition);
    }
    
    
    /**
     * 设置原始图片的宽高
     */
    public function initImageSrcWidthHeight()
    {
        $lastUnderlinePostion = strrpos($this->_imageName, '_');
        $lastXPosition = strrpos($this->_imageName, 'x');
        $lastPointPosition = strrpos($this->_imageName, '.');
        $this->_imageWidth = substr($this->_imageName, $lastUnderlinePostion + 1, $lastXPosition - $lastUnderlinePostion - 1);
        $this->_imageHeight = substr($this->_imageName, $lastXPosition + 1, $lastPointPosition - $lastXPosition - 1);

        if (empty($this->_imageWidth) && empty($this->_imageHeight)) {
            $imageFile = $this->getImageFile();
            $imageSize = @getimagesize($imageFile);
            if($imageSize) {
                $this->_imageWidth = $imageSize[0];
                $this->_imageHeight = $imageSize[1];
            }
        }
        
        if (empty($this->_imageWidth) && empty($this->_imageHeight)) {
            throw new Exception('Invalid source image dimensions.');
        }
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