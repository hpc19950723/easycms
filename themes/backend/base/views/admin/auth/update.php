<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminAuthItem */

$this->title = Yii::t('backend', Yii::$app->request->get('type')?'角色管理':'权限管理');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'role' == Yii::$app->request->get('type')?['role']:['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="admin-auth-item-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
