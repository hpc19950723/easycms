<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'username')->textInput(['placeholder' => '用户名','id' => 'username'])->label(false) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => '密码','id' => 'password'])->label(false) ?>

    <div class="login">
        <?= Html::submitButton('登录', ['style' => 'color: #fff;background-color:#3f88b8;font-size: 14px;height: 40px;border: none;margin: 0 auto 0 17px;padding: 0 20px 0 20px;-webkit-appearance:none;border-radius:0;cursor: pointer;', 'name' => 'login-button']) ?>
    </div>

<?php ActiveForm::end(); ?>