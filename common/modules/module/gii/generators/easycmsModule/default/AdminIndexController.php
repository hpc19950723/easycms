<?= '<?php' ?>
<?php
if(!isset($subModule)) {
    $subModule = '';
} else {
    $subModule = '\\' . $subModule;
}
?>

namespace common\modules\<?= $generator->name . $subModule ?>\controllers;

use Yii;

class IndexController extends \common\modules\admin\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
    
    }
}