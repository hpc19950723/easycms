<?php

namespace common\modules\admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "admin_menu".
 *
 * @property string $menu_id
 * @property string $name
 * @property integer $parent_id
 * @property string $route
 * @property string $icon
 * @property integer $position
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_menu';
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
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'route'], 'required'],
            ['position','default', 'value' => 0],
            [['parent_id', 'position'], 'integer'],
            [['name', 'route'], 'string', 'max' => 60],
            ['child_route', 'safe'],
            [['icon'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '菜单名称',
            'parent_id' => '主菜单',
            'route' => '路由',
            'child_route' => '高亮路由',
            'icon' => '图标',
            'position' => '位置',
        ];
    }
}
