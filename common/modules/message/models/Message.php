<?php

namespace common\modules\message\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\modules\core\components\Tools;


class Message extends \common\modules\core\models\CommonActiveRecord
{
    const TYPE_SYSTEM = 1;     //系统通知
    
    const TYPE_ACTIVITY = 2;    //活动通知
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
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
    
    
    public function fields()
    {
        return [
            'message_id',
            'title',
            'content',
            'type',
            'image' => function($model) {
                if($model->image) {
                    return Tools::getFileUrl($model->image);
                } else {
                    return;
                }
            },
            'created_at'
        ];
    }
    
    
    public function extraFields()
    {
        return [
            'unreadCount' => function($model) {
                return static::getUnreadCount($model->type);
            },
            'isRead' => function($model) {
                if($model->messageAction) {
                    return $model->messageAction->is_read;
                } else {
                    return Message::VALUE_NO;
                }
            }
        ];
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'sender_id' => '发送者ID',
            'receiver_id' => '接收者ID',
            'title' => '标题',
            'content' => '内容',
            'type' => '类型',
        ];
    }
    
    
    public static function getTypes()
    {
        return [
            static::TYPE_SYSTEM => '系统通知',
            static::TYPE_ACTIVITY => '活动通知'
        ];
    }
    
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            //标记当前记录为最新记录
            static::updateAll(['is_newest' => static::VALUE_NO], ['type' => $this->type]);
            $this->is_newest = static::VALUE_YES;
            $this->save();
        }
    }
    
    
    public function afterDelete()
    {
        if ($this->is_newest) {
            $model = static::find()->orderBy(['created_at' => SORT_DESC])
                    ->one();
            if ($model !== null) {
                $model->is_newest = static::VALUE_YES;
                $model->save();
            }
        }
    }
    
    
    public function getMessageAction()
    {
        return $this->hasOne(MessageAction::className(), ['message_id' => 'message_id'])
                ->where(['user_id' => Yii::$app->user->getId()]);
    }
    
    
    public function getMessageActions()
    {
        return $this->hasOne(MessageAction::className(), ['message_id' => 'message_id']);
    }
    
    
    /**
     * 获取未读消息数量
     * @param $type 消息类型
     */
    public static function getUnreadCount($type = null)
    {
        $query = static::find()->joinWith(['messageActions'])
                ->where(['>=', static::tableName() . '.created_at', Yii::$app->user->identity->created_at])
                ->andWhere('receiver_id = 0 or receiver_id = :receiverId', [':receiverId' => Yii::$app->user->getId()])
                ->andWhere(['user_id' => null]);
        
        if ($type !== null) {
            $query->andWhere(['type' => $type]);
        }

        return $query->count();
    }
}
