<?php

namespace common\modules\instagram\models\forms;

use Yii;
use common\modules\instagram\models\Collect;

class CollectForm extends \yii\base\Model
{
    public $user_id;
    
    public $instagram_user_id;
    
    public function rules()
    {
        return [
            [['user_id', 'instagram_user_id'], 'required'],
            ['instagram_user_id', 'unique', 'targetClass' => '\common\modules\instagram\models\Collect', 'filter' => function($query){
                $query->andWhere(['user_id' => $this->user_id]);
            }, 'message' => Yii::t('instagram', 'Please do not repeat to collection')],
        ];
    }
    
    public function save()
    {
        if($this->validate()) {
            $collect = new Collect();
            $collect->user_id = $this->user_id;
            $collect->instagram_user_id = $this->instagram_user_id;
            return $collect->save();
        } else {
            return false;
        }
    }
}