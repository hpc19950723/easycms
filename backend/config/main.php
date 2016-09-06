<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'layoutPath' => '@themes/backend/base/views/layouts',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'index',
    'bootstrap' => ['log'],
     'modules' => [
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@uploads/images/article',
            'uploadUrl' => '@resDomain/images/article',
            'imageAllowExtensions'=>['jpg','png','gif']
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\modules\admin\models\Admin',
            'loginUrl' => array('admin/index/login'),
            'enableAutoLogin' => true,
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/backend/base',
                'baseUrl' => '@web/themes/backend/base',
                'pathMap' => [
                    '@common/modules' => '@themes/backend/base',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'js' => [
                        'js/bootstrap.js',
                    ]
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => '/admin/index/error',
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'admin_auth_item',               //角色权限表
            'assignmentTable' => 'admin_auth_assignment',   //用户角色权限关系表
            'itemChildTable' => 'admin_auth_item_child',    //角色权限关系表
            'ruleTable' => 'admin_auth_rule'                //规则表
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
