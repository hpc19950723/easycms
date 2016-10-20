<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\message\models\Message;
use yii\redactor\widgets\Redactor;
use kartik\file\FileInput;
use common\modules\core\components\Tools;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?php if ($type == Message::TYPE_SYSTEM): ?>
    <?= $form->field($model, 'content')->textarea() ?>
    <?php elseif($type == Message::TYPE_ACTIVITY): ?>
    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => [
                $model->isNewRecord ? null : (empty($model->image) ? null : Tools::getFileUrl($model->image, 'message')),
            ],
            'initialPreviewAsData' => true,
            'initialPreviewFileType' => 'image',
            'overwriteInitial'=>true,
            'showUpload' => false,
            'showRemove' => false,
        ],
    ]);?>
    
    <?= $form->field($model, 'content')->widget(Redactor::className(),[
        'clientOptions' => [
            'lang' => 'zh_cn',
            'plugins' => ['fontcolor','imagemanager', 'fontsize'],
            'minHeight' => 300,
        ]
    ]) ?>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
