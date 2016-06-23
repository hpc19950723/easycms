<?php

use yii\helpers\Html;


$this->title = '更新菜单' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '菜单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->menu_id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="admin-menu-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
