<?php

namespace Imjoehaines\Flowder\Loader;

use Imjoehaines\Flowder\Persister\PersisterInterface;

class FileLoader implements LoaderInterface
{
    private $persister;

    public function __construct(PersisterInterface $persister)
    {
        $this->persister = $persister;
    }

    public function load($file)
    {
        $data = require $file;

        $table = pathinfo($file, PATHINFO_FILENAME);

        return $this->persister->persist($table, $data);
    }
}
