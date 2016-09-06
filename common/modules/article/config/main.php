<?php
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'article' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/modules/article/messages',
                    'sourceLanguage' => 'en-US',
                ]
            ]
        ]
    ],
    'admin' => [
        'modules' => [
            'article' => [
                'class' => 'common\modules\article\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'api' => [
        'modules' => [
            'article' => [
                'class' => 'common\modules\article\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];