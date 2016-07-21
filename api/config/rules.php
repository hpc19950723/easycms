<?php

return [
    ['class' => 'yii\rest\UrlRule', 'controller' => ['user/index']],
    "<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>"=>"<module>/<controller>/<action>",
];