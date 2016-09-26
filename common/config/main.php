<?php
return [
    'language' => 'zh-CN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone'=>'Asia/Chongqing',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache'
        ],
        'i18n' => [
            'translations' => [
                'backend*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'backend' => 'backend.php',
                        'backend/user' => 'user.php',
                        'backend/account' => 'account.php',
                        'backend/menus' => 'menus.php',
                        'backend/error' => 'error.php',
                    ],
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ]
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'weixin' => [
                    'class' => 'leap\oauth\Weixin',
                    'clientId' => 'wx9ddce7f20f8344da',
                    'clientSecret' => '96902b4a94b23d810495ce90f54558e7',
                ],
                'weixin_pc' => [
                    'class' => 'leap\oauth\Weixin',
                    'clientId' => 'wx5170ccf5cef58e47',
                    'clientSecret' => '7030d2d490b140fde4912f4867fe3811',
                ],
                'qq' => [
                    'class' => 'leap\oauth\Qq',
                    'clientId' => '1105322357',
                    'clientSecret' => 'CXMeLXSeWnVhaeIT',

                ],
            ]
        ],
    ],
];
