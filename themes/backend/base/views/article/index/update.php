<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Advert */

$this->title = '修改内容: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '内容管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="article-update">

    <?= $this->render('_form', [
        'model' => $model,
        'articleId' => $articleId
    ]) ?>

</div>
