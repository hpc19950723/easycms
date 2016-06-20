<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenus */

$this->title = $model->mobile;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User Management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('backend/user','ID'),
                'value' => $model->user_id,
            ],
            [
                'label' => Yii::t('backend/user','Mobile'),
                'value' => $model->mobile,
            ],
            [
                'label' => Yii::t('backend/user','Nickname'),
                'value' => $model->nickname,
            ],
            [
                'label' => Yii::t('backend/user','Real Name'),
                'value' => $model->real_name,
            ],
            [
                'label' => Yii::t('backend/user','Bio'),
                'value' => $model->bio,
            ],
            [
                'label' => Yii::t('backend/user','Gender'),
                'value' => User::getGenders()[$model->gender],
            ],
            [
                'label' => Yii::t('backend/user','Height'),
                'value' => $model->height,
            ],
            [
                'label' => Yii::t('backend/user','Weight'),
                'value' => $model->weight,
            ],
            [
                'label' => Yii::t('backend','Email'),
                'value' => $model->email,
            ],
            [
                'label' => Yii::t('backend/user','QQ'),
                'value' => $model->qq,
            ],
            [
                'label' => Yii::t('backend/user','Wechat'),
                'value' => $model->wechat,
            ],
            [
                'label' => Yii::t('backend/user','ID No.'),
                'value' => $model->id_no,
            ],
            [
                'label' => Yii::t('backend/user','User Type'),
                'value' => User::getUserType()[$model->user_type],
            ],
            [
                'label' => Yii::t('backend/user','Is Allow Post'),
                'value' => $model->allow_post,
            ],
            [
                'label' => Yii::t('backend/user','Appointment Fee'),
                'value' => $model->appointment_fee,
            ],
            [
                'label' => Yii::t('backend/user','Grade'),
                'value' => $model->grade,
            ],
            [
                'label' => Yii::t('backend','Status'),
                'value' => User::getStatus()[$model->status],
            ],
            [
                'label' => Yii::t('backend','Created At'),
                'value' => $model->created_at,
            ],
            [
                'label' => Yii::t('backend','Updated At'),
                'value' => $model->updated_at,
            ],
        ],
    ]) ?>

</div>
