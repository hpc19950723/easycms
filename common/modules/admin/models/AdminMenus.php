<?php

namespace common\modules\admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "admin_menus".
 *
 * @property string $menu_id
 * @property string $name
 * @property integer $parent_id
 * @property string $route
 * @property string $icon
 * @property integer $position
 */
class AdminMenus extends \yii\db\ActiveRecord
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
            [['icon'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => Yii::t('app', 'Menu ID'),
            'name' => Yii::t('app', 'Name'),
            'parent_id' => Yii::t('app', 'Parent'),
            'route' => Yii::t('app', 'Route'),
            'icon' => Yii::t('app', 'Icon'),
            'position' => Yii::t('app', 'Position'),
        ];
    }
}
