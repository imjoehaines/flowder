<?php

namespace Imjoehaines\Flowder;

use PHPUnit_Framework_Test;
use InvalidArgumentException;
use PHPUnit\Framework\BaseTestListener;
use Imjoehaines\Flowder\Loader\LoaderInterface;

class PhpUnitListener extends BaseTestListener
{
    public function __construct($path, LoaderInterface $loader)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('The file or directory "%s" does not exist!', $path));
        }

        $this->loader = $loader;
        $this->path = $path;
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->loader->load($this->path);
    }
}
