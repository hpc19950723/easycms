<?php

use yii\helpers\Html;

$this->title = '创建广告位';
$this->params['breadcrumbs'][] = ['label' => '广告位管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>