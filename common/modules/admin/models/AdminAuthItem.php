<?php

namespace common\modules\admin\models;

use yii\db\ActiveRecord;
use yii\rbac\Item;
use common\modules\admin\models\AdminAuthItemChild;

class AdminAuthItem extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_auth_item}}';
    }
    
    
    /**
     * 获取类型数据
     * @return array
     */
    public static function getTypes()
    {
        return [
            Item::TYPE_ROLE => '角色',
            Item::TYPE_PERMISSION => '权限',
        ];
    }
    
    
    public function getAdminAuthItemChildren()
    {
        return $this->hasMany(AdminAuthItemChild::className(), ['parent' => 'name']);
    }
    
    
    public function attributeLabels()
    {
        return [
            'name' => '权限名称',
            'type' => '类型',
            'description' => '描述',
            'rule_name' => '规则名',
            'data' => '数据',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}