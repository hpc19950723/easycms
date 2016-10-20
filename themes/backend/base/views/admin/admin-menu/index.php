<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\admin\models\AdminMenu;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-menu-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('创建菜单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '名称',
                'attribute' => 'name',
                'format'=>'raw',
                'value'=>function($m){
                    return $m->parent_id > 0 ? '|__' . $m->name : $m->name;
                }
            ],
            [
                'label' => '所属环境',
                'attribute' => 'env',
                'filter' => Html::activeDropDownList($searchModel,'env',AdminMenu::getEnvs(),['prompt'=>'全部','class'=>'form-control']),
                'value'=> function($m) {
                    return AdminMenu::getEnvs()[$m->env];
                }
            ],
            [
                'label' => '路由',
                'attribute' => 'route'
            ],
            [
                'label' => '图标',
                'attribute'=>'icon',
                'format'=>'raw',
                'value'=>function($m){
                    return Html::tag('i', $m->icon, ['class' => 'icon Hui-iconfont']);
                }
            ],
            [
                'label' => '位置',
                'attribute' => 'position',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
