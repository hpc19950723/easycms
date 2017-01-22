<?php

namespace common\modules\instagram\components;

use Yii;
use common\modules\instagram\components\Instagram;

class InstagramLike extends Instagram
{
    public function like($mediaId)
    {
        $result = $this->instagram->like($mediaId);
        if(isset($result['meta']['code']) && 200 == $result['meta']['code']) {
            return true;
        } else {
            return false;
        }
    }
    
    public function unlike($mediaId)
    {
        $result = $this->instagram->unlike($mediaId);
        if(isset($result['meta']['code']) && 200 == $result['meta']['code']) {
            return true;
        } else {
            return false;
        }
    }
}