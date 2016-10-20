<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenus */

$this->title = Yii::t('backend/account', 'Update Admin Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/account', 'Admin Account Management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="admin-menus-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
