<?php

namespace backend\models;

use yii\db\ActiveRecord;

class AdminAuthItemChild extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin_auth_item_child}}';
    }
}