<?php

namespace common\modules\user\models;

use Yii;

/**
 * This is the model class for table "user_group".
 *
 * @property integer $user_group_id
 * @property string $group_name
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name'], 'required'],
            [['group_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_group_id' => 'User Group ID',
            'group_name' => 'Group Name',
        ];
    }
}
