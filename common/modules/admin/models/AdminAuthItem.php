<?php

namespace backend\models;

use yii\db\ActiveRecord;
use backend\models\AdminAuthItemChild;

class AdminAuthItem extends ActiveRecord
{
    const T_ROLE = 1;   //角色
    const T_PERMISSION = 2;  //权限
    
    
    public static function tableName()
    {
        return '{{%admin_auth_item}}';
    }
    
    
    /**
     * 获取类型数据
     * @return array
     */
    public function getTypes()
    {
        return [
            self::T_ROLE => '角色',
            self::T_PERMISSION => '权限',
        ];
    }
    
    
    public function getAdminAuthItemChildren()
    {
        return $this->hasMany(AdminAuthItemChild::className(), ['parent' => 'name']);
    }
}