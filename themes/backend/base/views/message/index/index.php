<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\message\models\Message;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '消息管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建系统通知', ['create', 'type' => Message::TYPE_SYSTEM], ['class' => 'btn btn-success']) ?>
        <?= Html::a('创建活动通知', ['create', 'type' => Message::TYPE_ACTIVITY], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'filter'=> Html::activeDropDownList($searchModel,'type', Message::getTypes(),['prompt'=>'全部','class'=>'form-control']),                
                'attribute' => 'type',
                'value' => function($model) {
                    return Message::getTypes()[$model->type];
                }
            ],
            [
                'attribute' => 'title',
                'value' => function($model) {
                    return Html::encode(StringHelper::truncate($model->title, 25));
                }
            ],
            [
                'label' => '发布时间',
                'attribute' => 'created_at',
                'value' => 'created_at'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
