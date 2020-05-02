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
            'singleton-closure' => function(): \SplStack {
                return new \SplStack();
            },
            'singleton-service' => ['class' => \SplObjectStorage::class],
            'singleton-nested-service-class' => [
                ['class' => \SplFileInfo::class]
            ]
        ],
        'definitions' => [
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
