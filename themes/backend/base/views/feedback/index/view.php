<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Advert */

$this->title = $model->user->nickname;
$this->params['breadcrumbs'][] = ['label' => '意见反馈', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => '反馈人',
                'value' => $model->user->nickname,
            ],
            [
                'label' => '反馈内容',
                'value' => $model->content,
            ],
            [
                'label' => '反馈时间',
                'value' => $model->created_at,
            ],
        ],
    ]) ?>

</div>
