<?php

use yii\helpers\Html;

$this->title = Yii::t('backend/account', 'Create Admin Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/account', 'Admin Account Management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
