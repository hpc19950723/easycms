<?php

use yii\helpers\Html;

$this->title = Yii::t('backend/user', 'Update User: ') . ' ' . $model->mobile;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User Management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mobile, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>
