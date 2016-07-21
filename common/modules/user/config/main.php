<?php
return [
    'api' => [
        'modules' => [
            'user' => [
                'class' => 'common\modules\user\api\Module'
            ]
        ]
    ],
    'admin' => [
        'modules' => [
            'user' => [
                'class' => 'common\modules\user\admin\Module'
            ]
        ]
    ],
    'params' => [
        'user' => require('params.php')
    ]
];