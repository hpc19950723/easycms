<?php
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'message' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/modules/message/messages',
                    'sourceLanguage' => 'en-US',
                ]
            ]
        ]
    ],
    'admin' => [
        'modules' => [
            'message' => [
                'class' => 'common\modules\message\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'api' => [
        'modules' => [
            'message' => [
                'class' => 'common\modules\message\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];