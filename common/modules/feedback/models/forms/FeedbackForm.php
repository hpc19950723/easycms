<?php

namespace common\modules\feedback\models\forms;

use Yii;
use yii\base\Model;
use common\modules\feedback\models\Feedback;

class FeedbackForm extends Model
{
    public $user_id;
    public $content;
    const SCENARIOS_CREATE = 'create';


    public function rules()
    {
        return [
            [['user_id', 'content'], 'required'],
            ['user_id', 'exist', 'targetClass' => 'common\modules\user\models\User'],
        ];
    }
    
    
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_CREATE => ['user_id', 'content'],
         ];
        return array_merge( parent::scenarios(), $scenarios);
    }
    
    
    public function attributeLabels()
    {
        return [
            'content' => '内容'
        ];
    }
    
    
    public function save()
    {
        if($this->validate()) {
            $model = new Feedback();
            $model->user_id = $this->user_id;
            $model->content = $this->content;
            return $model->save();
        } else {
            return false;
        }
    }
}