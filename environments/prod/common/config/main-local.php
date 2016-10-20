<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'on afterOpen' => function($event) {
                $event->sender->createCommand("SET time_zone = '+8:00'")->execute();
            }
        ],
        //短信配置
        'sms' => [
            'class' => 'common\modules\core\components\Sms',
            'url' => 'http://api.maxleap.cn/2.0/requestSmsMsg',
            'appId' => '',
            'sessionToken' => '',
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
        ],
    ],
];
