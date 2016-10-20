<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.10.111;dbname=easycms',
            'username' => 'easycms',
            'password' => '123456',
            'charset' => 'utf8mb4',
            'on afterOpen' => function($event) {
                $event->sender->createCommand("SET time_zone = '+8:00'")->execute();
            }
        ],
        //短信配置
        'sms' => [
            'class' => 'common\modules\core\components\Sms',
            'url' => 'http://api.maxleap.cn/2.0/requestSmsMsg',
            'appId' => '56b17529169e7d000197d2d7',
            'sessionToken' => 'Hlm6mQBJyg2Xs0hafEJif_eyjPDKJhHlm9ZWhHr-l5k',
            'content' => [
                'register' => '验证码:%s, 15分钟有效, 立即注册',
                'reset_password' => '验证码:%s, 15分钟有效, 立即重置密码',
                'login' => '验证码:%s, 15分钟有效, 立即登录',
                'user_bind' => '验证码:%s, 15分钟有效, 立即绑定'
            ]
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
