<?php

return [
    'securityCode' => [
        //短信验证码到过期时间
        'expiration' => 15 * 60
    ],
    'config' => require(__DIR__ . '/config.php')
];