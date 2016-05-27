<?php

return [
    'admin' => [
        'class' => 'app\modules\admin\Module',
        'defaultRoute' => 'index',
    ],
    'user_admin' => [
        'class' => 'app\modules\user\admin\Module',
        'defaultRoute' => 'index'
    ]
];