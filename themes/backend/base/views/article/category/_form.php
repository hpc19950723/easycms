<?php
use common\modules\article\models\ArticleCategory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identifier')->textInput(['maxlength' => true])->hint('唯一标识符,只能包含小写字母,数值,下划线(_). 如, "example_page".') ?>
    
    <?php
        $belongTo = [ 0 => '主分类'];
        if (isset($categoryId)) {
            $belongTo += ArticleCategory::find()->select(['name','category_id'])->where(['parent_id' => 0, 'status' => ArticleCategory::STATUS_ACTIVE])->andWhere(['!=', 'category_id', $categoryId])->indexBy('category_id')->column();
        } else {
            $belongTo += ArticleCategory::find()->select(['name','category_id'])->where(['parent_id' => 0, 'status' => ArticleCategory::STATUS_ACTIVE])->indexBy('category_id')->column();
        }
    ?>
    <?= $form->field($model, 'parent_id')->dropdownList($belongTo) ?>

    <?= $form->field($model, 'position')->textInput() ?>
    


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
