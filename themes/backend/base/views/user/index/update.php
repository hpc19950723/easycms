<?php

use yii\helpers\Html;

$this->title = Yii::t('backend/user', 'Update User: ') . ' ' . $model->mobile;
$this->params['breadcrumbs'][] = ['label' => '会员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="user-update">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>
