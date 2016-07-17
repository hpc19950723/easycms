<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('backend/account', 'Update Password');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/account', 'System Management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/account', 'Update Password')];
?>
<div class="admin-menus-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="password-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'old_password')->passwordInput()->label(Yii::t('backend/account','Old Password')) ?>
        
        <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('backend/account','Password')) ?>
        
        <?= $form->field($model, 'password2')->passwordInput()->label(Yii::t('backend/account','Confirm Password')) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
