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
                'weibo' => [
                    'class' => 'leap\oauth\Weibo',
                    'clientId' => '917618014',
                    'clientSecret' => '7ae714a47cf36fcf53a7032f501472b3',
                ],
                'instagram' => [
                    'class' => 'leap\oauth\Instagram',
                    'clientId' => '6f0ff8c77aff4c0486d32ef3d38dc611',
                    'clientSecret' => '5bd32dbe19804912abde44502f3a113b',
                ],
            ]
        ],
    ],
];
