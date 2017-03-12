<?php

namespace Imjoehaines\Flowder\Loader;

interface LoaderInterface
{
    /**
     * Load the given thing and return an array of data
     *
     * @param mixed $thingToLoad
     * @return array
     */
    public function load($thingToLoad);
}
