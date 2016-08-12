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
        <div class="form-group field-<?= $name ?>">
            <label for="<?= $name ?>" class="control-label"><?= $section['label'] ?></label>
            <?php
            if(!empty($section['source_model'])) {
                $params = [
                    'config['. $name .']',
                    $section['value'],
                    Yii::createObject($section['source_model'])->toArray(),
                    ['class' => 'form-control', 'id' => $name]
                ];
            } else {
                $params = [
                    'config['. $name .']',
                    $section['value'],
                    ['class' => 'form-control', 'id' => $name]
                ];
            }
            ?>
            <?= call_user_func_array(array('yii\helpers\Html', $section['frontend_type']), $params) ?>
            <p class="help-block"><?= !isset($section['comment'])?'':$section['comment'] ?></p>
        </div>
        <?php endforeach; ?>
        

        <div class="form-group">
            <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
