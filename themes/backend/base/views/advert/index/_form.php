<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\advert\models\Advert;
use kartik\file\FileInput;
use common\modules\core\components\Tools;
use common\modules\advert\models\AdvertPosition;
use dosamigos\datepicker\DatePicker;
?>

<div class="advert-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'name')->textInput() ?>
    
    <?= $form->field($model, 'position_id')->dropDownList(AdvertPosition::find()->select(['name', 'position_id'])->indexBy('position_id')->column()) ?>
    
    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => [
                $model->isNewRecord ? null : (empty($model->image) ? null : Tools::getFileUrl($model->image, 'avatar')),
            ],
            'initialPreviewAsData' => true,
            'initialPreviewFileType' => 'image',
            'overwriteInitial'=>true,
            'showUpload' => false,
            'showRemove' => false,
        ],
    ]);?>
    
    <?= $form->field($model, 'link')->textInput() ?>
    
    <?= $form->field($model, 'start_time')->widget(
         DatePicker::className(), [
            'inline' => false, 
            'template' => '{addon}{input}',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
    ]);?>
    
    <?= $form->field($model, 'end_time')->widget(
         DatePicker::className(), [
            'inline' => false, 
            'template' => '{addon}{input}',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
    ]);?>
    
    <?= $form->field($model, 'position')->textInput() ?>
    
    <?= $form->field($model, 'status')->dropDownList(Advert::getStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
