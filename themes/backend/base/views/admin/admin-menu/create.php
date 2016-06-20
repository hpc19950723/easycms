<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Create Admin Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin Menu'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
