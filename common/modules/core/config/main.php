<?php
return [
    'api' => [
        'modules' => [
            'core' => [
                'class' => 'common\modules\core\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'params' => require('params.php')
];