<?php

/* @var $this yii\web\View */

$this->title = '欢迎来到后台管理中心';
?>
<h3>欢迎来到后台管理中心</h3>
<table  class="table">
    <tbody>
        <tr>
            <td>Yii 版本</td>
            <td><?php echo Yii::getVersion(); ?></td>
        </tr>
    	<tr>
            <td>操作系统</td>
            <td><?php echo php_uname('s'); ?> <?php echo php_uname('r'); ?></td>
        </tr>
        <tr>
            <td>PHP 版本</td>
            <td><?php echo PHP_VERSION; ?></td>
        </tr>
        <tr>
            <td>MySQL 版本</td>
            <td><?php echo Yii::$app->db->pdo->getAttribute(PDO::ATTR_SERVER_VERSION); ?></td>
        </tr>
        
                                 
    </tbody>
</table>