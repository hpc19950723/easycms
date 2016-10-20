<?php

namespace common\modules\module\gii\generators\easycmsModule;

use yii\gii\CodeFile;
use yii\helpers\Html;
use Yii;
use yii\helpers\StringHelper;
use common\modules\module\models\Module;

/**
 * This generator will generate the skeleton code needed by a module.
 *
 * @property string $controllerNamespace The controller namespace of the module. This property is read-only.
 * @property boolean $modulePath The directory that contains the module class. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\Generator
{
    public $name;
    public $title;
    public $type;
    
    const TYPE_ONLY_ADMIN = 1;
    const TYPE_ONLY_API = 2;
    const TYPE_ADMIN_API = 3;

    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['name']);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Easycms Module Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to generate the skeleton code needed by a Yii module.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'title', 'type'], 'required'],
            [['name', 'title'], 'filter', 'filter' => 'trim'],
            [['name'], 'match', 'pattern' => '/^[\w]+$/', 'message' => 'Only word characters'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '模块ID',
            'title' => '模块名称',
            'type' => '模块类型',
        ];
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'name' => '模块ID, e.g., <code>admin, user</code>.',
            'title' => '模块名称，如<code>后台模块, 用户模块</code>',
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        if (Yii::$app->hasModule($this->moduleID)) {
            $link = Html::a('try it now', Yii::$app->getUrlManager()->createUrl($this->moduleID), ['target' => '_blank']);

            return "The module has been generated successfully. You may $link.";
        }

        $output = <<<EOD
<p>The module has been generated successfully.</p>
EOD;

        return $output;
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['main.php', 'params.php', 'ApiModule.php', 'AdminModule.php', 'AdminIndexController.php', 'ApiIndexController.php'];
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];
        $modulePath = $this->getModulePath();
       
        $files=array_merge($files,$this->generateConfig($modulePath));
        $files=array_merge($files,$this->generateModule($modulePath));
        $files=array_merge($files,$this->generateController($modulePath));
        return $files;
    }
    
    
    public function save($files, $answers, &$results)
    {
        if (parent::save($files, $answers, $results)) {
            $model = Module::findOne(['name' => $this->name]);
            if($model === null) {
                $model = new Module();
                $model->setAttributes([
                    'name' => $this->name,
                    'title' => $this->title,
                    'version' => '1.0.0',
                    'enabled_api' => Module::VALUE_YES,
                    'enabled_admin' => Module::VALUE_YES,
                    'delete' => Module::VALUE_YES,
                    'status' => Module::STATUS_ACTIVE
                ]);
                $model->save();
            }
            return true;
        }
        return false;
    }
    
    
    private function generateConfig($modulePath)
    {
        $files[] = new CodeFile(
            $modulePath . '/config/main.php',
            $this->render('main.php')
        );
        
        $files[] = new CodeFile(
            $modulePath . '/config/params.php',
            $this->render('params.php')
        );
        
        $files[] = new CodeFile(
            $modulePath . '/messages/zh-CN/' . $this->name . '.php',
            $this->render('messages.php')
        );
        return $files;
    }
    
    
    private function generateModule($modulePath)
    {
        switch($this->type) {
            case static::TYPE_ONLY_ADMIN:
                $files[] = new CodeFile(
                    $modulePath . '/Module.php',
                    $this->render('AdminModule.php')
                );
            break;
            case static::TYPE_ONLY_API:
                $files[] = new CodeFile(
                    $modulePath . '/api/Module.php',
                    $this->render('ApiModule.php', [
                        'subModule' => 'api'
                    ])
                );
            break;
            case static::TYPE_ADMIN_API:
                $files[] = new CodeFile(
                    $modulePath . '/api/Module.php',
                    $this->render('ApiModule.php', [
                        'subModule' => 'api'
                    ])
                );
                $files[] = new CodeFile(
                    $modulePath . '/admin/Module.php',
                    $this->render('AdminModule.php', [
                        'subModule' => 'admin'
                    ])
                );
            break;
        }
        return $files;
    }
    
    
    private function generateController($modulePath)
    {
        switch($this->type) {
            case static::TYPE_ONLY_ADMIN:
                $files[] = new CodeFile(
                    $modulePath . '/controllers/IndexController.php',
                    $this->render('AdminIndexController.php')
                );
            break;
            case static::TYPE_ONLY_API:
                $files[] = new CodeFile(
                    $modulePath . '/api/controllers/IndexController.php',
                    $this->render('ApiIndexController.php', [
                        'subModule' => 'api'
                    ])
                );
            break;
            case static::TYPE_ADMIN_API:
                $files[] = new CodeFile(
                    $modulePath . '/api/controllers/IndexController.php',
                    $this->render('ApiIndexController.php', [
                        'subModule' => 'api'
                    ])
                );
                $files[] = new CodeFile(
                    $modulePath . '/admin/controllers/IndexController.php',
                    $this->render('AdminIndexController.php', [
                        'subModule' => 'admin'
                    ])
                );
            break;
        }
        return $files;
    }
    
    
    /**
     * Validates [[moduleClass]] to make sure it is a fully qualified class name.
     */
    public function validateModuleClass()
    {
        
    }

    public function getModuleClassName()
    {
        return ucwords($this->name);
    }
    
    public function getModuleId()
    {
        return $this->name;
    }
    
    public function getModulePath()
    {
        return Yii::getAlias('@common').'\modules\\'.$this->name;
    }

    /**
     * @return string the controller namespace of the module.
     */
    public function getControllerNamespace()
    {
        return substr($this->moduleClass, 0, strrpos($this->moduleClass, '\\')) . '\controllers';
    }
    
    
    public static function getTypes()
    {
        return [
            self::TYPE_ONLY_ADMIN => '仅存在后台管理',
            self::TYPE_ONLY_API => '仅存在API',
            self::TYPE_ADMIN_API => '存在API和后台管理',
        ];
    }
}
