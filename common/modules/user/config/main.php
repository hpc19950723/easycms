<?php
return [
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
    'params' => [
        'user' => require('params.php')
    ]
];