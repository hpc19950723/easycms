<?php

return [
    'admin' => [
        'module' => [
            'class' => 'common\modules\user\admin\Module',
            'defaultRoute' => 'index',
        ]
    ],
    'api' => [
        'module' => [
            'class' => 'common\modules\user\api\Module',
            'defaultRoute' => 'index',
        ]
    ]
];