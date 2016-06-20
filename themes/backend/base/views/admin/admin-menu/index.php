<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend/menu', 'Admin Menu');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend/menu', 'Create Admin Menu'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             [
                'label'=>'Name',
                'format'=>'raw',
                'value'=>function($m){
                    return $m->parent_id > 0 ? '&nbsp;&nbsp;' . $m->name : $m->name;
                }
            ],
            'parent_id',
            'route',
            [
                'label'=>'Icon',
                'format'=>'raw',
                'value'=>function($m){
                    return Html::label($m->icon, null,
                                ['class' => 'icon Hui-iconfont']
                    );
                }
            ],
            'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
