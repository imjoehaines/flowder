<?php

namespace Imjoehaines\Flowder\Loader;

final class PhpFileLoader implements LoaderInterface
{
    /**
     * Loads the given PHP file that should return an array of data
     *
     * @param string $file
     * @return array
     */
    public function load($file)
    {
        $table = pathinfo($file, PATHINFO_FILENAME);

        $data = require $file;

        yield $table => $data;
    }
}
