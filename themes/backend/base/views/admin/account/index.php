<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\modules\admin\models\Admin;


$this->title = Yii::t('backend/account', 'Admin Account Management');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <p>
        <?= Html::a(Yii::t('backend/account', 'Create Admin Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => '#',
                'value' => 'admin_id',
                'options'=> ['width'=>"80px"]
            ],
            [
                'label' => Yii::t('backend/user','Username'),
                'value' => function($m) {
                    return $m->username;
                },
            ],
            [
                'label' => Yii::t('backend','Email'),
                'value' => function($m) {
                    return $m->email;
                },
            ],
            [
                'label' => Yii::t('backend','Mobile'),
                'value' => function($m) {
                    return $m->mobile;
                },
            ],
            [
                'label' => Yii::t('backend','Status'),
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
                    return Html::label(Admin::getStatus()[$m->status],null,['class' => $className]);
                },
            ],
            [
                'label' => Yii::t('backend/account','Remark'),
                'value' => function($m) {
                    return $m->remark;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'options' => ['width'=>'70px']
            ],
        ],
    ]); ?>

</div>
