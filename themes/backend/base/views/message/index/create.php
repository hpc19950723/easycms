<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenus */

$this->title = '创建消息';
$this->params['breadcrumbs'][] = ['label' => '消息列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type
    ]) ?>

</div>
