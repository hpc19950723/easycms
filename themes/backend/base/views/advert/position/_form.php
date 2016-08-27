<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\advert\models\AdvertPosition;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'name')->textInput() ?>
    
    <?= $form->field($model, 'identifier')->textInput() ?>
    
    <?= $form->field($model, 'width')->textInput() ?>
    
    <?= $form->field($model, 'height')->textInput() ?>
    
    <?= $form->field($model, 'description')->textInput() ?>
    
    <?= $form->field($model, 'status')->dropDownList(AdvertPosition::getStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
