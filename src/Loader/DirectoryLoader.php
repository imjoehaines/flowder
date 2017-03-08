<?php

namespace Imjoehaines\Flowder\Loader;

use Imjoehaines\Flowder\Persister\PersisterInterface;

class DirectoryLoader implements LoaderInterface
{
    private $persister;

    public function __construct(PersisterInterface $persister)
    {
        $this->persister = $persister;
    }

    public function load($directory)
    {
        $phpFiles = glob(rtrim($directory, '/') . '/*.php');

        $fileLoader = new FileLoader($this->persister);

        foreach ($phpFiles as $phpFile) {
            $fileLoader->load($phpFile);
        }
    }
}
