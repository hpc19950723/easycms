<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模块管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <p>
        <?= Html::a('创建模块', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button('导入模块', ['class' => 'btn btn-success btn-import-module']) ?>
        <?php $form = ActiveForm::begin(['action' => ['import'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
        <input type="file" multiple="" accept="application/x-zip-compressed" class="import-module"  id="importModule" name="importModule">
        <?php ActiveForm::end(); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            'name',
            'version',
            [
                'attribute' => 'enabled_api',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->enabled_api, ['checked' => (boolean)$model->enabled_api, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->module_id, 'type' => 'enabled_api'])]);
                }
            ],
            [
                'attribute' => 'enabled_admin',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->enabled_admin, ['checked' => (boolean)$model->enabled_admin, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->module_id, 'type' => 'enabled_admin'])]);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->status, ['checked' => (boolean)$model->status, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->module_id, 'type' => 'status'])]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{zip} {delete}',
                'buttons' => [
                    'zip' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('admin', '模块打包'),
                            'aria-label' => Yii::t('admin', '模块打包'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-compressed"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]); ?>

</div>
<script>
$(function(){
    $('.btn-import-module').click(function(){
        $('.import-module').click();
    });
    
    $('.import-module').change(function(){
        $(this).parent('form').submit();
    });
});
</script>