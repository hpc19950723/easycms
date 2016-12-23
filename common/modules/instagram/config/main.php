<?php
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'instagram' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/modules/instagram/messages',
                    'sourceLanguage' => 'en-US',
                ]
            ]
        ]
    ],
    'admin' => [
        'modules' => [
            'instagram' => [
                'class' => 'common\modules\instagram\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'api' => [
        'modules' => [
            'instagram' => [
                'class' => 'common\modules\instagram\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];