<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\admin\models\Module;
use yii\helpers\Url;
use common\modules\core\models\CommonActiveRecord;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模块管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            'name',
            'dir',
            'version',
            [
                'attribute' => 'has_api',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->has_api, ['checked' => (boolean)$model->has_api, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->module_id, 'type' => 'has_api'])]);
                }
            ],
            [
                'attribute' => 'has_admin',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->has_admin, ['checked' => (boolean)$model->has_admin, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->module_id, 'type' => 'has_admin'])]);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->status, ['checked' => (boolean)$model->status, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->module_id, 'type' => 'status'])]);
                }
            ],
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>