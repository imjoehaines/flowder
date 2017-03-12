<?php

namespace Imjoehaines\Flowder;

use Imjoehaines\Flowder\Loader\LoaderInterface;
use Imjoehaines\Flowder\Persister\PersisterInterface;
use Imjoehaines\Flowder\Truncator\TruncatorInterface;

class Flowder
{
    public function __construct(
        $thingToLoad,
        TruncatorInterface $truncator,
        LoaderInterface $loader,
        PersisterInterface $persister
    ) {
        $this->thingToLoad = $thingToLoad;
        $this->truncator = $truncator;
        $this->loader = $loader;
        $this->persister = $persister;
    }

    public function loadFixtures()
    {
        $data = $this->loader->load($this->thingToLoad);

        foreach ($data as $table => $tableData) {
            $this->truncator->truncate($table);
            $this->persister->persist($table, $tableData);
        }
    }
}
