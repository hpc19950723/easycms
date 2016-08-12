<?php

namespace common\modules\admin\controllers;

use Yii;
use common\modules\admin\components\BaseController;
use common\modules\admin\models\CoreConfig;
use common\modules\admin\models\forms\CoreConfigForm;

class ConfigController extends BaseController
{
    public function actionIndex()
    {
        $moduleId = Yii::$app->request->get('module_id');
        $class = Yii::$app->request->get('class');
        $config = $this->edit(Yii::$app->request->get('module_id'), Yii::$app->request->get('class'));
        
        $rules = $attributeLabels = [];
        $model = new CoreConfigForm();
        foreach($config['sections'] as $name => $section) {
            $model->$name = $section['value'];
            $attributeLabels[$name] = $section['label'];
            if(isset($section['rules'])) {
                foreach($section['rules'] as $rule) {
                    array_unshift($rule, $name);
                    $rules[] = $rule;
                }
            }
        }
        $model->setPathPrefix($moduleId . '/' . $class . '/');
        $model->setRules($rules);
        $model->setAttributeLabels($attributeLabels);
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', '保存成功');
        }

        return $this->render('edit',[
            'config' => $config ,
            'model' => $model
        ]);
    }
    
    
    /**
     * 编辑配置信息
     * @param string $class
     * @return array
     */
    public function edit($moduleId, $class)
    {
        if(isset(Yii::$app->params[$moduleId]['config'][$class])) {
            $config = Yii::$app->params[$moduleId]['config'][$class];
        } else {
            throw new \yii\web\NotFoundHttpException('没有找到配置项');
        }
        
        foreach($config['sections'] as $key => &$section) {
            $path = $moduleId . '/' . $class . '/' . $key;
            
            $model = CoreConfig::find()->where('path=:path', [':path'=>$path])->one();
            if($model !== null) {
                $section['value'] = $model->value;
            } else {
                $section['value'] = $section['default'];
            }
        }
        CoreConfig::cacheAll(); //重新缓存配置
        return $config;
    }
}