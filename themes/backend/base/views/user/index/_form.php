<?php
use common\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use common\modules\core\components\Tools;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => [
                $model->isNewRecord ? null : (empty($model->avatar) ? null : Tools::getFileUrl($model->avatar)),
            ],
            'initialPreviewAsData' => true,
            'initialPreviewFileType' => 'image',
            'overwriteInitial'=>true,
            'showUpload' => false,
            'showRemove' => false,
        ],
    ]);?>
    
    <?= $form->field($model, 'mobile')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'bio')->textarea() ?>
    
    <?= $form->field($model, 'real_name')->textInput() ?>

    <?= $form->field($model, 'gender')->dropdownList(User::getGenders()) ?>
    
    <?= $form->field($model, 'email')->textInput() ?>
    
    <?= $form->field($model, 'qq')->textInput() ?>
    
    <?= $form->field($model, 'wechat')->textInput() ?>
    
    <?= $form->field($model, 'id_no')->textInput() ?>
        

    <?php if($model->isNewRecord): ?>
    <?= $form->field($model, 'user_type')->dropdownList(User::getUserType()) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'status')->dropdownList(User::getStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
