<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminAuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-auth-item-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a(Yii::t('admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('admin', 'Delete'), ['delete', 'id' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'type',
            'description:ntext',
            'rule_name',
            'data:ntext',
            'created_at:datetime',
            "updated_at:datetime",
        ],
    ]) ?>

</div>
