<?php

namespace Imjoehaines\Flowder\Test;

class FileRequireCounter
{
    public static $count = 0;

    public static function reset()
    {
        static::$count = 0;
    }
}
