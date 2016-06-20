<?php
use common\modules\admin\models\Admin;
use common\modules\admin\models\forms\AccountForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput()->label(Yii::t('backend/user','Username')) ?>
    
    <?php $model->password = ''; ?>
    <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('backend/account','Password')) ?>
    
    <?= $form->field($model, 'email')->textInput()->label(Yii::t('backend','Email')) ?>
   
    <?= $form->field($model, 'mobile')->textInput()->label(Yii::t('backend','Mobile')) ?>
    
    <?= $form->field($model, 'remark')->textInput()->label(Yii::t('backend/account','Remark')) ?>
    
    <?php
    $roles = [];
    foreach(AccountForm::getRoles() as $role) {
        $roles[$role] = $role;
    }
    ?>
    <?= $form->field($model, 'role')->dropdownList($roles)->label(Yii::t('backend','Role')) ?>
    
    <?php
    //如果当前为创建账号,则默认设置status为开启
    if($model->isNewRecord) {
        $model->status = Admin::STATUS_ACTIVE;
    }
    ?>
    <?= $form->field($model, 'status')->dropdownList(Admin::getStatus())->label(Yii::t('backend','Status')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
