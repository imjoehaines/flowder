<?php

namespace Imjoehaines\Flowder\Loader;

interface LoaderInterface
{
    /**
     * Load the given thing and return an iterable with data
     *
     * @param mixed $thingToLoad
     * @return iterable
     */
    public function load($thingToLoad);
}
