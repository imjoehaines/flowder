<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Loader;

interface LoaderInterface
{
    /**
     * Load the given thing and return an iterable with data
     *
     * @param mixed $thingToLoad
     * @return iterable<mixed>
     */
    public function load($thingToLoad): iterable;
}
