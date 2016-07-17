<?php
$params = [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
];

$configFiles = \yii\helpers\FileHelper::findFiles(\Yii::getAlias('@common/modules'), [
    'only' => ['params.php'],
    'recursive' => true
]);

foreach($configFiles as $file) {
    $moduleLength = strlen(\Yii::getAlias('@common/modules/'));
    $moduleId = substr($file, $moduleLength, strpos($file, '/', $moduleLength) - $moduleLength);
    $params[$moduleId] = require($file);
}

return $params;