<?php

return [
    'components' => [
        'i18n' => [
            'translations' => [
                'admin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/modules/admin/messages',
                    'sourceLanguage' => 'en-US',
                ]
            ]
        ]
    ],
    'admin' => [
        'modules' => [
            'admin' => [
                'class' => 'common\modules\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];