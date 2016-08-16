<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        上面的错误发生在服务器处理您的请求的时候
    </p>
    <p>
        如果您认为是服务器错误请联系我们,谢谢!
    </p>

</div>
