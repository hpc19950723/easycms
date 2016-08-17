<?php

namespace common\modules\core\api\controllers;

use Yii;
use yii\web\HttpException;
use yii\base\UserException;

class IndexController extends \common\modules\core\api\components\BaseController
{
    public $defaultErrorName;
    
    public $defaultErrorMessage;
    
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultErrorMessage ?: Yii::t('yii', 'An internal server error occurred.');
        }
        
        return self::formatResult($code, $message);
    }
}