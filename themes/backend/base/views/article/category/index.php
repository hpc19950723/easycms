<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
//
//$this->title = Yii::t('backend/menus', 'Admin Menues');
$this->title = '分类管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建分类', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => '名称',
                'attribute' => 'name',
                'format'=>'raw',
                'value'=>function($m){
                    return $m->parent_id > 0 ? '|__' . $m->name : $m->name;
                }
            ],
            [
                'label' => '标识符',
                'attribute' => 'identifier',
            ],
            [
                'label' => '状态',
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->status, ['checked' => (boolean)$model->status, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->category_id])]);
                }
            ],
            [
                'header' => '操作',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>
