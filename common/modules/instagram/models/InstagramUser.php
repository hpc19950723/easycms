<?php

namespace common\modules\instagram\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Transaction;

class InstagramUser extends \common\modules\core\models\CommonActiveRecord
{
    public static function tableName() {
        return '{{%instagram_user}}';
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
    
    public static function batchInsert($users)
    {
        if(!empty($users)) {
            $transaction = Yii::$app->db->beginTransaction(Transaction::SERIALIZABLE);
            try {
                $instagramUserIds = [];
                foreach($users as $user) {
                    $instagramUserIds[] = $user['id'];
                }
                $instagramUserIds = static::find()->where(['instagram_user_id' => $instagramUserIds])->select('instagram_user_id')->column();
                
                $dateTime = date('Y-m-d H:i:s');
                $rows = [];
                foreach($users as $user) {
                    if(!in_array($user['id'], $instagramUserIds)) {
                        $rows[] = [
                            $user['id'], $user['username'], $user['full_name'], $user['profile_picture'], $dateTime, $dateTime
                        ];
                    }
                }
                
                if(!empty($rows)) {
                    Yii::$app->db->createCommand()->batchInsert(static::tableName(), ['instagram_user_id', 'username', 'full_name', 'profile_picture', 'created_at', 'updated_at'], $rows)->execute();
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }
}