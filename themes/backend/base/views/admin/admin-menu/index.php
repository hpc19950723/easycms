<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-menu-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建菜单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format'=>'raw',
                'value'=>function($m){
                    return $m->parent_id > 0 ? '|__' . $m->name : $m->name;
                }
            ],
            'route',
            [
                'attribute'=>'icon',
                'format'=>'raw',
                'value'=>function($m){
                    return Html::tag('i', $m->icon, ['class' => 'icon Hui-iconfont']);
                }
            ],
            'position',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
