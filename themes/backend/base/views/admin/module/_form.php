<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\admin\models\Module;
?>

<div class="module-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'dir')->textInput() ?>

    <?= $form->field($model, 'version')->textInput() ?>

    <?= $form->field($model, 'has_api')->dropDownList(Module::getYesNo()) ?>

    <?= $form->field($model, 'has_admin')->dropDownList(Module::getYesNo()) ?>

    <?= $form->field($model, 'status')->dropDownList(Module::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
