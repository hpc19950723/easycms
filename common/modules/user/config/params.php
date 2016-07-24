<?php

return [
    'uploads' => [
        'avatar' =>  [                       //头像上传配置路径
            'dir' => '@uploads/avatar/',
            'prefix' => 'AVAT',
        ],
    ],
    'securityCode' => [
        //短信验证码到过期时间
        'expiration' => 15 * 60
    ]
];