<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\advert\models\AdvertPosition;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告位管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <p>
        <?= Html::a('创建广告位', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'identifier',
            'width',
            'height',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return AdvertPosition::getStatus()[$model->status];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
