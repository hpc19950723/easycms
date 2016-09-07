<?php
namespace common\modules\article\models;

use Yii;
use common\modules\core\models\CommonActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\modules\article\models\ArticleCategory;

class Article extends CommonActiveRecord
{
    const STATUS_INACTIVE = 0;    //未激活
    const STATUS_ACTIVE = 1;    //激活

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


    public static function tableName()
    {
        return '{{%article}}';
    }

    public function attributeLabels()
    {
        return [
            'title' => '文章名称',
            'category_id' => '所属分类',
            'image' => '图片',
            'content' => '内容',
            'link' => '外部链接',
            'status' => '状态',
            'created_at' => '创建时间'
        ];
    }

    public static function getStatus()
    {
        return [
            self::STATUS_INACTIVE => '未激活',
            self::STATUS_ACTIVE => '激活',
        ];
    }
    
    
    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['category_id' => 'category_id']);
    }
    
    
    public function afterDelete()
    {
        if (0 < $this->category_id) {
            ArticleCategory::findOne($this->category_id)->updateCounters(['items_count' => -1]);
        }
    }
    
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if (0 < $this->category_id) {
                ArticleCategory::findOne($this->category_id)->updateCounters(['items_count' => 1]);
            }
        } else {
            if($changedAttributes['category_id'] != $this->category_id) {
                if(0 < $this->category_id) {
                    ArticleCategory::findOne($this->category_id)->updateCounters(['items_count' => 1]);
                }

                if(0 < $changedAttributes['category_id']) {
                    ArticleCategory::findOne($changedAttributes['category_id'])->updateCounters(['items_count' => -1]);
                }
            }
        }
    }
}
