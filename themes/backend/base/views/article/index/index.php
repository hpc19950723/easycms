<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\modules\article\models\Article;
use common\modules\article\models\ArticleCategory;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建文章', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'value' => 'article_id',
            ],
            [
                'attribute' => 'title',
            ],
            [
                'attribute' => 'category_id',
                'filter'=> Html::activeDropDownList($searchModel,'category_id', ArticleCategory::getCategories(),['prompt'=>'全部','class'=>'form-control']),
                'value' => function($m) {
                    return $m->category->name;
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter'=> Html::activeDropDownList($searchModel,'status', Article::getStatus(),['prompt'=>'全部','class'=>'form-control']),
                'value' => function($model) {
                    return Html::input('checkbox', 'status', $model->status, ['checked' => (boolean)$model->status, 'data-toggle' => 'switch', 'data-on-color' => 'primary', 'data-off-color' => 'default', 'class' => 'status', 'data-ajax-url' => Url::to(['update-status', 'id' => $model->article_id])]);
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => 'created_at',
            ],
            [
                'template' => '{update} {delete}',
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
    ]); ?>
</div>
