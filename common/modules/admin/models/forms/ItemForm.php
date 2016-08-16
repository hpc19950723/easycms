<?php

namespace common\modules\admin\models\forms;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\modules\admin\models\AdminAuthItem;
use yii\web\NotFoundHttpException;
use yii\rbac\Item;

class ItemForm extends Model
{
    public $type;
    
    public $name;
    
    public $description;
    
    public $children;
    
    private $_authItemModel;
    
    private $_oldChildren;
    
    //定义场景
    const SCENARIOS_CREATE = 'create';
    const SCENARIOS_UPDATE = 'update';
    const SCENARIOS_DELETE = 'delete';
    
    
    public function __construct($config = array()) {
        parent::__construct($config);
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',    // 自己根据数据库字段修改
                'updatedAtAttribute' => 'updated_at',    // 自己根据数据库字段修改
                'value' => new Expression('NOW()'),         // 自己根据数据库字段修改
            ]
        ];
    }
    
    
    public function rules() {
        return [
            ['type', 'default', 'value' => Item::TYPE_PERMISSION],
            ['type', 'in', 'range' => [Item::TYPE_ROLE,  Item::TYPE_PERMISSION]],
            [['name', 'type'], 'required'],
            ['name', 'unique', 'targetClass' => '\common\modules\admin\models\AdminAuthItem', 'when' => function(){
                return $this->isNewRecord || ($this->_authItemModel->name != $this->name);
            }, 'message' => '此名称已经被占用。', 'on' => [self::SCENARIOS_CREATE, self::SCENARIOS_UPDATE]],
            ['description', 'safe'],
            ['children', 'safe'],
        ];
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'type' => '类型',
            'description' => '描述',
        ];
    }
    
    
    /**
     * 场景设置
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios(){
        $scenarios = [
            self:: SCENARIOS_CREATE => ['name', 'type', 'description', 'children'],
            self:: SCENARIOS_UPDATE => ['name', 'type', 'description', 'children'],
            self:: SCENARIOS_DELETE => ['name'],
         ];
        return array_merge( parent:: scenarios(), $scenarios);
    }
    
    
    /**
     * 获取是否为创建订单
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_authItemModel === null;
    }
    
    
    /**
     * 角色&权限的创建方法
     * @return boolean 返回成功或者失败的状态值
     */
    public function addItem()
    {
        //实例化AuthManager类
        $auth = Yii::$app->authManager;
        if($this->type == Item::TYPE_ROLE){
            $item = $auth->createRole($this->name);
        }else{
            $item = $auth->createPermission($this->name);
        }
        $item->description = !empty($this->description)?$this->description:$this->name;
       
       if($auth->add($item)) {
            if($this->children) {
                foreach($this->children as $child) {
                    $childItem = $auth->getPermission($child);
                    $auth->addChild($item, $childItem);
                }
            }
       } else {
           return false;
       }
    }
    
    
    public function romoveItem()
    {
        if($this->validate()){
            $auth = Yii::$app->authManager;
            $item = $auth->getRole($this->name)?:$auth->getPermission($this->name);
            return $auth->remove($item);
        }
        return false;
    }
    
    
    /**
     * 获取角色或权限数据
     * @param type $id
     * @return \backend\models\ItemForm
     * @throws NotFoundHttpException
     */
    public function getItem($id)
    {
        $this->_authItemModel = AdminAuthItem::findOne(['name' => $id]);
        if(!$this->_authItemModel)
            throw new NotFoundHttpException('The requested page does not exist.');
        
        $this->setAttributes($this->_authItemModel->getAttributes());
        $this->_oldChildren = $this->children = $this->_authItemModel->getAdminAuthItemChildren()->select('child')->column();
        
        return $this;
    }
    
    
    public function updateItem($name)
    {
        $auth = Yii::$app->authManager;
        if($this->type == Item::TYPE_ROLE){
            $item = $auth->createRole($this->name);
            $item->description = $this->description?:'创建['.$this->name.']角色';
        }else{
            $item = $auth->createPermission($this->name);
            $item->description = $this->description?:'创建['.$this->name.']权限';
        }
       
        if($auth->update($name, $item)) {
            //删除关联的权限
            $deleteChildren = array_diff($this->_oldChildren, $this->children);
            foreach($deleteChildren as $child) {
                $childItem = $auth->getPermission($child);
                $auth->removeChild($item, $childItem);
            }
            
            //添加关联的权限
            $addChildren = array_diff($this->children, $this->_oldChildren);
            foreach($addChildren as $child) {
                $childItem = $auth->getPermission($child);
                $auth->addChild($item, $childItem);
            }
       } else {
           return false;
       }
    }
}
