<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Loader;

final class PhpFileLoader implements LoaderInterface
{
    /**
     * Loads the given PHP file that should return an iterable with data
     *
     * @param string $file
     * @return iterable<string, iterable>
     */
    public function load($file): iterable
    {
        $table = pathinfo($file, PATHINFO_FILENAME);

        $data = require $file;

        yield $table => $data;
    }
}
