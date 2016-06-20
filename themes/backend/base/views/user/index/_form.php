<?php
use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true])->label(Yii::t('backend/user', 'Nickname')) ?>
    
    <?= $form->field($model, 'bio')->textarea()->label(Yii::t('backend/user', 'Bio')) ?>
    
    <?= $form->field($model, 'real_name')->textInput()->label(Yii::t('backend/user', 'Real Name')) ?>

    <?= $form->field($model, 'gender')->dropdownList(User::getGenders())->label(Yii::t('backend/user', 'Gender')) ?>
    
    <?= $form->field($model, 'height')->textInput()->label(Yii::t('backend/user', 'Height')) ?>
    
    <?= $form->field($model, 'weight')->textInput()->label(Yii::t('backend/user', 'Weight')) ?>
    
    <?= $form->field($model, 'email')->textInput()->label(Yii::t('backend', 'Email')) ?>
    
    <?= $form->field($model, 'qq')->textInput()->label(Yii::t('backend/user', 'QQ')) ?>
    
    <?= $form->field($model, 'wechat')->textInput()->label(Yii::t('backend/user', 'Wechat')) ?>
    
    <?= $form->field($model, 'id_no')->textInput()->label(Yii::t('backend/user', 'ID No.')) ?>
        
    <?= $form->field($model, 'appointment_fee')->textInput()->label(Yii::t('backend/user', 'Appointment Fee')) ?>
        
    <?php if($model->isNewRecord): ?>
    <?= $form->field($model, 'user_type')->dropdownList(User::getUserType())->label(Yii::t('backend/user', 'User Type')) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'allow_post')->dropdownList(User::getAllowPostOptions())->label(Yii::t('backend/user', 'Is Allow Post')) ?>
    
    <?= $form->field($model, 'status')->dropdownList(User::getStatus())->label(Yii::t('backend', 'Status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
