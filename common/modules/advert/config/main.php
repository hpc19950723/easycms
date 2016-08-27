<?php
return [
    'admin' => [
        'modules' => [
            'advert' => [
                'class' => 'common\modules\advert\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'api' => [
        'modules' => [
            'advert' => [
                'class' => 'common\modules\advert\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];