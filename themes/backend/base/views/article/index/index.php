<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\modules\article\models\Article;
use common\modules\article\models\ArticleCategory;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '内容管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建内容', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
        $categories[0] = '单页面';
        $categories = array_merge($categories, ArticleCategory::getCategories());
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'value' => 'article_id',
            ],
            [
                'attribute' => 'title',
                'value' => function($model) {
                    return StringHelper::truncate($model->title, 20);
                }
            ],
            [
                'attribute' => 'category_id',
                'filter'=> Html::activeDropDownList($searchModel,'category_id', $categories,['prompt'=>'全部','class'=>'form-control']),
                'value' => function($model) {
                    if ($model->category_id == 0) {
                        return '单页面';
                    } else {
                        return $model->category->name;
                    }
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
