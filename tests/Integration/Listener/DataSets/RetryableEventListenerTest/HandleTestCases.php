<?php

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

return [
    'Happy path' => [
        'exceptionIterations' => [],
        'handleEvent' => function (MockObject $event, TestCase $test): MockObject {
            return $event;
        },
    ],
    'Recovers after 1st failure' => [
        'exceptionIterations' => [0],
        'handleEvent' => function (MockObject $event, TestCase $test): MockObject {
            return $event;
        },
    ],
    'Never recovers. Exceeds retry attempts' => [
        'exceptionIterations' => [0, 1, 2, 3, 4,5 ,6 ,7, 8, 9],
        'handleEvent' => function (MockObject $event, TestCase $test): MockObject {
            return $event;
        },
    ],
];
