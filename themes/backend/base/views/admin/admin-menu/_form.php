<?php
use common\modules\admin\models\AdminMenu;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="admin-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php
        $belongTo = [ 0 => '主菜单'];
        $belongTo += AdminMenu::find()->select(['name','menu_id'])->where(['parent_id' => 0])->indexBy('menu_id')->column();
    ?>
    <?= $form->field($model, 'parent_id')->dropdownList($belongTo) ?>
    
    <?= $form->field($model, 'env')->dropdownList(AdminMenu::getEnvs()) ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'child_route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
