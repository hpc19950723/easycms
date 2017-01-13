<?php

namespace common\modules\instagram\components;

use Yii;
use common\modules\instagram\components\Instagram;
use common\modules\instagram\models\InstagramUser;

class FollowAndFollowed extends Instagram
{
    public function getFollows()
    {
        return $this->getCache('getSelfFollows', null, true, 600, function($users){
            InstagramUser::batchInsert($users);
        });
    }
    
    public function getFollowedBy()
    {
        return $this->getCache('getSelfFollowedBy', null, true, 600, function($users){
            InstagramUser::batchInsert($users);
        });
    }
}