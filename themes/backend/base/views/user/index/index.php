<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\user\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'User Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => '#',
                'attribute' => 'user_id',
                'value' => 'user_id',
                'options'=> ['width'=>"80px"]
            ],
            [
                'label' => Yii::t('backend/user','Mobile'),
                'attribute' => 'mobile',
                'value' => 'mobile'
            ],
            [
                'label' => Yii::t('backend','Email'),
                'attribute' => 'email',
                'value' => 'email'
            ],
            'nickname',
            [
                'label' => Yii::t('backend', 'User Type'),
                'attribute' => 'user_type',
                'filter'=> Html::activeDropDownList($searchModel,'user_type',User::getUserType(),['prompt'=>'全部','class'=>'form-control']),
                'value' => function($m) {
                    return User::getUserType()[$m->user_type];
                },
                'options'=> ['width'=>"90px"]
            ],
            [
                'label' => Yii::t('backend','Status'),
                'attribute' => 'status',
                'filter'=> Html::activeDropDownList($searchModel,'status',User::getStatus(),['prompt'=>'全部','class'=>'form-control']),
                'format' => 'raw',
                'value' => function($m) {
                    switch($m->status) {
                        case 0:
                            $className = 'text-danger';
                            break;
                        case 1:
                            $className = 'text-success';
                            break;
                        case 2:
                            $className = 'text-warning';
                    }
                    return Html::label(User::getStatus()[$m->status],null,['class' => $className]);
                },
                'options'=> ['width'=>"90px"]
            ],
            [
                'label' => Yii::t('backend','Created At'),
                'value' => 'created_at'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'70px']
            ],
        ],
    ]); ?>

</div>
