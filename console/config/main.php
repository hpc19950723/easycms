<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'admin_auth_item',               //角色权限表
            'assignmentTable' => 'admin_auth_assignment',   //用户角色权限关系表
            'itemChildTable' => 'admin_auth_item_child',    //角色权限关系表
            'ruleTable' => 'admin_auth_rule'                //规则表
        ]
    ],
    'params' => $params,
];
