<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\modules\user\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/rules.php')
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['payment'],
                    'logFile' => '@api/runtime/logs/Payment/notify.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 50,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;

                if ($response->format != 'jsonp' && $response->data !== null && !isset($response->data['errcode'])) {
                    if(isset($response->data['code']) && $response->data['code'] > 0) {
                        $errcode = $response->data['code'];
                    } else if(isset($response->data['status'])) {
                        $errcode = $response->data['status'];
                    } else {
                        $errcode = $response->statusCode;
                    }

                    $response->data = [
                        'errcode' => $errcode,
                        'errmsg' => isset($response->data['message'])?$response->data['message']:$response->statusText,
                    ];
                    $response->statusCode = 200;
                }
            },
        ],
    ],
    'params' => $params,
];
