<?php

return [
    'home' => [
        'title' => '首页分类ID',
        'sections' => [
            'category_id' => [
                'label' => '分类ID',
                'frontend_type' => 'dropDownList',
                'rules' => [
                    ['required'],
                ],
                'source_model' => 'common\modules\admin\models\sources\Yesno',
                'default' => '',
                'sort' => 1,
            ], 
            'backend_welcome' => [
                'label' => '欢迎语',
                'frontend_type' => 'textarea',
                'rules' => [
                    ['required'],
                ],
                'default' => '',
                'sort' => 1,
                'comment' => '修改后台登录后的欢迎语',
            ],
            'footer_copyright' => [
                'label' => '备案信息',
                'frontend_type' => 'textarea',
                'rules' => [
                    ['required'],
                ],
                'default' => '',
                'sort' => 2,
            ],
        ],
    ],
    'weixin' => [
        'title' => '微信配置',
        'sections' => [
            'app_id' => [
                'label' => 'AppId(应用ID)',
                'frontend_type' => 'textinput',
                'default' => '',
                'comment' => '微信后台开发者中心获取',
                'sort' => 1,
            ],
            'app_secret' => [
                'label' => 'AppSecret(应用密钥)',
                'frontend_type' => 'textinput',
                'default' => '',
                'comment' => '微信后台开发者中心获取',
                'sort' => 2,
            ],
            'merchant_id' => [
                'label' => 'MchId(商户号)',
                'frontend_type' => 'textinput',
                'default' => '',
                'comment' => '微信审核通过,邮件内获取',
                'sort' => 3,
            ],
            'app_key' => [
                'label' => 'AppKey(API密钥)',
                'frontend_type' => 'textinput',
                'default' => '',
                'comment' => '登陆微信支付<a href="//pay.weixin.qq.com" target="_blank">商户平台</a>, 账户设置-密码安全-API安全',
                'sort' => 4,
            ]
        ]
    ]
];