<?php
namespace common\modules\advert\api\controllers;

use Yii;
use common\modules\advert\models\AdvertPosition;
use common\modules\core\components\Tools;

class IndexController extends \common\modules\core\api\components\BaseController
{
    /**
     * @inheritdoc
     */
    public function actionView()
    {
        Tools::addQueryParams(['expand' => 'adverts']);
        $model = AdvertPosition::findOne(['identifier' => Yii::$app->request->get('identifier'), 'status' => AdvertPosition::STATUS_ACTIVE]);
        return static::formatSuccessResult($model);
    }
}