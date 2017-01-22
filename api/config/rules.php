<?php

return [
    ['class' => 'yii\rest\UrlRule', 'controller' => ['user/index']],
    "<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:[\w-]+>"=>"<module>/<controller>/<action>",
];