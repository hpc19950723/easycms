<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\admin\models\AdminAuthItem;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = AdminAuthItem::getTypes()[$this->context->type] . '管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-auth-item-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建'. AdminAuthItem::getTypes()[$this->context->type], ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>

</div>
