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
                'source_model' => 'common\modules\admin\models\source\Yesno',
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
];