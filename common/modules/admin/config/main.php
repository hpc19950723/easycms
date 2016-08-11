<?php
return [
    'admin' => [
        'modules' => [
            'admin' => [
                'class' => 'common\modules\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require('params.php')
];