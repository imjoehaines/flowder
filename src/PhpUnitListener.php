<?php

namespace Imjoehaines\Flowder;

use PHPUnit_Framework_Test;
use InvalidArgumentException;
use PHPUnit\Framework\BaseTestListener;
use Imjoehaines\Flowder\Loader\LoaderInterface;
use Imjoehaines\Flowder\Persister\PersisterInterface;
use Imjoehaines\Flowder\Truncator\TruncatorInterface;

class PhpUnitListener extends BaseTestListener
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

    public function startTest(PHPUnit_Framework_Test $test)
    {
        $data = $this->loader->load($this->thingToLoad);

        foreach ($data as $table => $tableData) {
            $this->truncator->truncate($table);
            $this->persister->persist($table, $tableData);
        }
    }
}
