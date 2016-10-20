<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Advert;
use common\components\Tools;

/* @var $this yii\web\View */
/* @var $model common\models\Advert */

$this->title = $model->advert_id;
$this->params['breadcrumbs'][] = ['label' => '首页广告', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->advert_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->advert_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => '广告名称',
                'value' => $model->advert_name,
            ],
            [
                'label' => '广告类型',
                'value' => Advert::getAdvertType()[$model->advert_type],
            ],
            [
                'label' => '广告图片',
                'format' => 'raw',
                'value' => Html::img(Tools::getFileUrl($model->advert_photo, 'images/idcard'), ['width' => '200px'])
            ],
            [
                'label' => '添加时间',
                'value' => $model->created_at,
            ],
            [
                'label' => '对应ID',
                'value' => $model->to_id,
            ],
        ],
    ]) ?>

</div>
