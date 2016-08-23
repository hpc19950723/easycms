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

class IndexController extends \common\modules\core\api\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
    
    }
}