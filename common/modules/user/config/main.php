<?php

return [
    'components' => [
        'i18n' => [
            'translations' => [
                'user' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/modules/user/messages',
                    'sourceLanguage' => 'en-US',
                ]
            ]
        ]
    ],
    'api' => [
        'modules' => [
            'user' => [
                'class' => 'common\modules\user\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'admin' => [
        'modules' => [
            'user' => [
                'class' => 'common\modules\user\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];