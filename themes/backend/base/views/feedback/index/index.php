<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;

$this->title = '意见反馈';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header' => '序号',
                'class' => 'yii\grid\SerialColumn'
            ],
            [
                'header' => '反馈人',
                'attribute' => 'nickname',
                'value' => function($m) {
                    return $m->user->nickname;
                }
            ],
            [
                'header' => '反馈内容',
                'value' => function($m){
                    return StringHelper::truncate(strip_tags($m->content),20);
                },
            ],
            [
                'header' => '反馈时间',
                'attribute' => 'created_at',
                'value' => 'created_at'
            ],
            [
                'header' => '操作',
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'120px'],
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>

</div>
