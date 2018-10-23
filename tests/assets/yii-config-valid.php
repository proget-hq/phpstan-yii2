<?php

return [
    'container' => ['singletons' => [
        'closure' => function(): \SplStack {
            return new \SplStack();
        },
        'service' => ['class' => \SplObjectStorage::class],
        'nested-service-class' => [
            ['class' => \SplFileInfo::class]
        ]
    ]]
];
