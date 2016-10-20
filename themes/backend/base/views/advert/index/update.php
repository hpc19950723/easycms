<?php

use yii\helpers\Html;

$this->title = '更新广告';
$this->params['breadcrumbs'][] = ['label' => '广告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-update">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <?= $this->render('_form', [
        'model' => $model,
        'advertPosition' => $advertPosition
    ]) ?>


</div>