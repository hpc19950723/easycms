<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\admin\models\AdminAuthItem;
use yii\grid\GridView;

?>

<div class="admin-auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if($model->isNewRecord && 'role' != Yii::$app->request->get('type')): ?>
    <?= $form->field($model, 'type')->dropDownList(AdminAuthItem::getTypes()) ?>
    <?php endif; ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <div>
        <label>选择权限</label>
    </div>
    <?php $children = $model->children; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width'=>'70px'],
                'name' => 'ItemForm[children]',
                'checkboxOptions' => function ($model, $key, $index, $column) use ($children) {
                    return [
                        'value' => $model->name,
                        'checked' => !empty($children) ? in_array($model->name, $children) : false,
                    ];
                }
            ],
            [
                'label' => Yii::t('backend', 'Name'),
                'value' => function($m) {
                    return $m->name;
                }
            ],
            [
                'label' => Yii::t('backend', 'Description'),
                'value' => function($m) {
                    return $m->description;
                },
            ]
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->getIsNewRecord() ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->getIsNewRecord() ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
