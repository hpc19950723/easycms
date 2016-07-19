<?php
namespace common\modules\core\components\image;

use yii\base\Exception;

class ImageAdapter 
{
    const ADAPTER_GD    = 'GD';
    const ADAPTER_GD2   = 'GD2';
    const ADAPTER_IM    = 'IMAGEMAGIC';
    const ADAPTER_IME   = 'IMAGEMAGIC_EXTERNAL';

    public static function factory($adapter)
    {
        switch( $adapter ) {
            case self::ADAPTER_GD:
                return new ImageAdapterGd();

            case self::ADAPTER_GD2:
                return new ImageAdapterGd2();

            case self::ADAPTER_IM:
                return new ImageAdapterImagemagic();

            case self::ADAPTER_IME:
                return new ImageAdapterImagemagicExternal();

            default:
                throw new Exception('Invalid adapter selected.');
        }
    }
}