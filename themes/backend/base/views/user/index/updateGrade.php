<?php

use yii\helpers\Html;

$this->title = '更新等级';
$this->params['breadcrumbs'][] = ['label' => '用户等级管理', 'url' => ['grade']];
$this->params['breadcrumbs'][] = '更新等级';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_gradeForm', [
        'model' => $model,
    ]) ?>


</div>
