<?php

namespace common\modules\admin\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/iconfont.css',
        'css/index.css',
        'css/flat-ui.min.css',
    ];
    public $js = [
        'js/flat-ui.min.js',
        'js/index.js',
    ];
    
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
