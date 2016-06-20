<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'nickname') ?>

    <?= $form->field($model, 'avatar') ?>

    <?php // echo $form->field($model, 'bio') ?>

    <?php // echo $form->field($model, 'real_name') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'length') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'qq') ?>

    <?php // echo $form->field($model, 'wechat') ?>

    <?php // echo $form->field($model, 'id_no') ?>

    <?php // echo $form->field($model, 'user_type') ?>

    <?php // echo $form->field($model, 'allow_post') ?>

    <?php // echo $form->field($model, 'appointment_fee') ?>

    <?php // echo $form->field($model, 'grade') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'opened_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>