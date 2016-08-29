<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\modules\advert\models\Advert;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <p>
        <?= Html::a('创建广告', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => '广告位名称',
                'value' => function($model) {
                    return $model->advertPosition->name;
                }
            ],
            'name',
            [
                'label' => '广告图片',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::img(Yii::createObject('common\modules\core\components\Image')->init($model->image)->setKeepFrame(false)->resize(250)->__toString());
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->status, ['checked' => (boolean)$model->status, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->advert_id])]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
