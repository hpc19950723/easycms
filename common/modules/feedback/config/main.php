<?php
return [
    'admin' => [
        'modules' => [
            'feedback' => [
                'class' => 'common\modules\feedback\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'api' => [
        'modules' => [
            'feedback' => [
                'class' => 'common\modules\feedback\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];