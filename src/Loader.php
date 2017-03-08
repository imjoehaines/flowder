<?php

namespace Imjoehaines\Flowder;

use Imjoehaines\Flowder\Persister\PersisterInterface;

class Loader
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
