<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\redactor\widgets\Redactor;
use common\modules\article\models\ArticleCategory;
use common\modules\article\models\Article;
use common\modules\core\components\Tools;
use yii\helpers\Url;
?>

<div class="advert-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?php
        $categories[0] = '单页面';
        $categories = array_merge($categories, ArticleCategory::getCategories());
    ?>
    <?= $form->field($model, 'category_id')->dropdownList($categories,['prompt'=>'-- 选择所属分类 --'])?>

    <?= $form->field($model, 'status')->dropdownList(Article::getStatus())?>
    
    <?php
    $pluginOptions = [
        'initialPreview'=>[
            $model->isNewRecord ? null : ($model->image ? Html::img(Tools::getFileUrl($model->image, 'article'), ['width' => '200px']) : null)
        ],
        'overwriteInitial'=>true,
        'initialPreviewFileType' => 'image',
        'overwriteInitial'=>true,
        'showUpload' => false,
        'showRemove' => false,
    ];
    if (isset($articleId)) {
        $pluginOptions['initialPreviewConfig'] = [
            ['url'=> Url::to(['delete-image','article_id' => $articleId])],
        ];
    }
    ?>
    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => $pluginOptions
    ]);?>
    
    <?= $form->field($model, 'content')->widget(Redactor::className(),[
        'clientOptions' => [
            'lang' => 'zh_cn',
            'plugins' => ['fontcolor','imagemanager', 'fontsize'],
            'minHeight' => 300,
        ]
    ]) ?>
    
    <?= $form->field($model, 'link', ['options' => ['class' => 'form-group']])->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
