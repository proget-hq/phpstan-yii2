<?php

return [
    'container' => ['singletons' => [
        'no-return-type' => function () {
            return new \ArrayObject();
        }
    ]]
];
