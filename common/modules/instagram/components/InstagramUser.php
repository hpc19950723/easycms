<?php

namespace common\modules\instagram\components;

use Yii;
use common\modules\instagram\components\Instagram;

class InstagramUser extends Instagram
{
    public function getStatisticData()
    {
        $selfMediaRecent =  $this->getCache('selfMediaRecent', true, 600);
        $data = [
            'total_likes' => 0,
            'avg_likes' => 0,
            'total_comments' => 0,
            'avg_comments' => 0
        ];
        $totalMedia = 0;
        if($selfMediaRecent['meta']['code'] == 200 && !empty($selfMediaRecent['data'])) {
            foreach($selfMediaRecent['data'] as $media) {
                $data['total_likes'] += $media['likes']['count'];
                $data['total_comments'] += $media['comments']['count'];
                $totalMedia++;
            }
        }
        
        if($totalMedia > 0) {
            $data['avg_likes'] = floor($data['total_likes'] / $totalMedia);
            $data['avg_comments'] = floor($data['total_comments'] / $totalMedia);
        }
        return $data;
    }
}