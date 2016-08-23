<?php
use backend\gii\generators\easycmsModule\Generator;
?>
<?= '<?php' ?>

return [
<?php if($generator->type == Generator::TYPE_ONLY_ADMIN): ?>
    'admin' => [
        'modules' => [
            '<?= $generator->name ?>' => [
                'class' => 'common\modules\<?= $generator->name ?>\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
<?php elseif($generator->type == Generator::TYPE_ONLY_API): ?>
    'api' => [
        'modules' => [
            '<?= $generator->name ?>' => [
                'class' => 'common\modules\<?= $generator->name ?>\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
<?php else: ?>
    'admin' => [
        'modules' => [
            '<?= $generator->name ?>' => [
                'class' => 'common\modules\<?= $generator->name ?>\admin\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
    'api' => [
        'modules' => [
            '<?= $generator->name ?>' => [
                'class' => 'common\modules\<?= $generator->name ?>\api\Module',
                'defaultRoute' => 'index'
            ]
        ]
    ],
<?php endif; ?>
    'params' => require(__DIR__ . '/params.php'),
];