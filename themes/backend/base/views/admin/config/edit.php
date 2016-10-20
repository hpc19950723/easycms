<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $config['title'];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="update-document">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="config-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php foreach($config['sections'] as $name => $section): ?>
        <?php
        $params = [];
        if(!empty($section['source_model'])) {
            $params = [
                Yii::createObject($section['source_model'])->toArray(),
                ['class' => 'form-control', 'id' => $name]
            ];
        }
        $field = $form->field($model, $name)->label($section['label'])->hint(!isset($section['comment'])?'':$section['comment']);
        ?>
        <?= call_user_func_array(array($field, $section['frontend_type']), $params) ?>
        <?php endforeach; ?>
        

        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
