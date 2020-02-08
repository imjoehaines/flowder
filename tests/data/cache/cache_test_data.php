<?php declare(strict_types=1);

use Imjoehaines\Flowder\Test\FileRequireCounter;

return [[
    'count' => ++FileRequireCounter::$count,
]];
