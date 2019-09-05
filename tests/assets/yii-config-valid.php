<?php

use Proget\Tests\PHPStan\Yii2\Yii\MyActiveRecord;

return [
    'components' => [
        'customComponent' => [
            'class' => MyActiveRecord::class,
        ],
        'customInitializedComponent' => new MyActiveRecord(),
    ],
    'container' => [
        'singletons' => [
            'closure' => function(): \SplStack {
                return new \SplStack();
            },
            'service' => ['class' => \SplObjectStorage::class],
            'nested-service-class' => [
                ['class' => \SplFileInfo::class]
            ]
        ]
    ]
];
