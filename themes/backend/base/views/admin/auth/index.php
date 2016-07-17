<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\admin\models\AdminAuthItem;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-auth-item-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建权限', ['create-item'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'type',
                'value' => function($m) {
                    return AdminAuthItem::getTypes()[$m->type];
                }
            ],
            'description:ntext',
            'rule_name',
            'data:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    switch($action)
                    {
                        case 'view':
                            return  Url::to(['auth/view-item','id' => $model->name]);
                        case 'update':
                            return  Url::to(['auth/update-item','id' => $model->name]);
                        case 'delete':
                            return  Url::to(['auth/delete-item','id' => $model->name]);
                        break;
                    }

                },
            ],
        ],
    ]); ?>

</div>
