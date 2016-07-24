<?php

return [
    //短信配置
    'sms' => [
        'url' => 'http://api.maxleap.cn/2.0/requestSmsMsg',
        'content' => [
            'register' => '验证码:%s, 15分钟有效, 立即注册',
            'reset_password' => '验证码:%s, 15分钟有效, 立即重置密码',
            'login' => '验证码:%s, 15分钟有效, 立即登录',
        ],
        'account' => [
            'appId' => '576a0f49169e7d0001387726',
            'sessionToken' => 'Oe107dV3z1dAU2LIVZYC4f7bOhA4LhHmkSdWhHr-l5k'
        ]
    ]
];