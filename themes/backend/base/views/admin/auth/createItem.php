<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AdminAuthItem */

$this->title = Yii::t('backend', Yii::$app->request->get('type')?'创建角色':'创建权限');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', Yii::$app->request->get('type')?'角色管理':'权限管理'), 'url' => 'role' == Yii::$app->request->get('type')?['role']:['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-auth-item-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ]) ?>

</div>
