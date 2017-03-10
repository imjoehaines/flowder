<?php

namespace Imjoehaines\Flowder\Loader;

use Imjoehaines\Flowder\Persister\PersisterInterface;

class FileLoader implements LoaderInterface
{
    public function load($file)
    {
        $table = pathinfo($file, PATHINFO_FILENAME);

        $data = require $file;

        return [$table => $data];
    }
}
