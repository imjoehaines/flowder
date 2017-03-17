<?php

namespace Imjoehaines\Flowder;

use Imjoehaines\Flowder\Loader\LoaderInterface;
use Imjoehaines\Flowder\Persister\PersisterInterface;
use Imjoehaines\Flowder\Truncator\TruncatorInterface;

final class Flowder
{
    /**
     * @param mixed $thingToLoad
     * @param LoaderInterface $loader
     * @param TruncatorInterface $truncator
     * @param PersisterInterface $persister
     */
    public function __construct(
        $thingToLoad,
        LoaderInterface $loader,
        TruncatorInterface $truncator,
        PersisterInterface $persister
    ) {
        $this->thingToLoad = $thingToLoad;
        $this->loader = $loader;
        $this->truncator = $truncator;
        $this->persister = $persister;
    }

    /**
     * @return void
     */
    public function loadFixtures()
    {
        $data = $this->loader->load($this->thingToLoad);

        foreach ($data as $table => $tableData) {
            $this->truncator->truncate($table);
            $this->persister->persist($table, $tableData);
        }
    }
}
