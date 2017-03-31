<?php

namespace Imjoehaines\Flowder\Loader;

final class PhpFileLoader implements LoaderInterface
{
    /**
     * Loads the given PHP file that should return an iterable with data
     *
     * @param string $file
     * @return iterable
     */
    public function load($file)
    {
        $table = pathinfo($file, PATHINFO_FILENAME);

        $data = require $file;

        yield $table => $data;
    }
}
