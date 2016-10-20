<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\admin\models\AdminAuthItem;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Role Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-auth-item-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Role'), ['create-item','type' => 'role'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['width' => '70px'],
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
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    switch($action)
                    {
                        case 'update':
                            return  Url::to(['auth/update-item', 'id' => $model->name, 'type' => 'role']);
                        case 'delete':
                            return  Url::to(['auth/delete-item','id' => $model->name, 'type' => 'role']);
                        break;
                    }
                },
                'options' => ['width' => '90px'],
            ],
        ],
    ]); ?>

</div>
