<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../../common/config/bootstrap-local.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

$dir = \Yii::getAlias('@common/modules');
$handle = opendir($dir);
if ($handle === false) {
    throw new InvalidParamException("Unable to open directory: $dir");
}
while (($file = readdir($handle)) !== false) {
    if ($file === '.' || $file === '..' || is_file($file)) {
        continue;
    }
    $path = $dir . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'main.php';
    if (file_exists($path)) {
        $moduleConfig = require($path);
        if(isset($moduleConfig['api'])) {
            $moduleConfig = array_merge($moduleConfig, $moduleConfig['api']);
        }
        if(isset($moduleConfig['params'])) {
            $moduleConfig['params'][$file] = $moduleConfig['params'];
        }
        unset($moduleConfig['api']);
        unset($moduleConfig['admin']);
        $config = array_merge_recursive($config, $moduleConfig);
    }
}

$application = new yii\web\Application($config);
$application->run();
