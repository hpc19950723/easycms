<?php

use yii\helpers\Html;
use common\modules\admin\models\AdminAuthItem;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminAuthItem */

$this->title = Yii::t('backend', '创建' . AdminAuthItem::getTypes()[$this->context->type]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', AdminAuthItem::getTypes()[$this->context->type] . '管理'), 'url' => 'role' == Yii::$app->request->get('type')?['role']:['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-auth-item-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ]) ?>

</div>
