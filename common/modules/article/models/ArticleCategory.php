<?php

namespace common\modules\article\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class ArticleCategory extends \common\modules\core\models\CommonActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category}}';
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
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'identifier' => '标识符',
            'parent_id' => '所属分类',
            'position' => '排序',
            'description' => '描述',
            'status' => '状态',
        ];
    }
    
    
    public function getParentCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'parent_id']);
    }
    
    
    /**
     * 获取类目
     * @return array
     */
    public static function getCategories($parentId = 0)
    {
        $models = static::find()->where(['parent_id' => $parentId, 'status' => static::STATUS_ACTIVE])
                ->orderBy(['position' => SORT_ASC])
                ->all();

        $categories = [];
        foreach($models as $model) {
            $childrenCategories = static::getCategires($model->category_id);
            if(empty($childrenCategories)) {
                $categories[$model->category_id] = $model->name;
            } else {
                $categories[$model->name] = $childrenCategories;
            }
        }
        return $categories;
    }
    
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if(0 < $this->parent_id) {
                //修改父类children_count加1
                static::findOne($this->parent_id)->updateCounters(['children_count' => 1]);
            }
        } else {
            if($changedAttributes['parent_id'] != $this->parent_id){
                if(0 < $this->parent_id) {
                    //修改父类children_count加1
                    static::findOne($this->parent_id)->updateCounters(['children_count' => 1]);
                }

                if(0 < $changedAttributes['parent_id']) {
                    //修改源父类children_count减1
                    static::findOne($changedAttributes['parent_id'])->updateCounters(['children_count' => -1]);
                }
            }
        }
    }
    
    
    public function afterDelete()
    {
        if(0 < $this->parent_id) {
            static::findOne($this->parent_id)->updateCounters(['children_count' => -1]);
        }
    }
}
