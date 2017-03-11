<?php

namespace Imjoehaines\Flowder\Loader;

class CachingLoader implements LoaderInterface
{
    private $cache = [];

    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function load($thingToLoad)
    {
        if (empty($this->cache[$thingToLoad])) {
            $this->cache[$thingToLoad] = $this->loader->load($thingToLoad);
        }

        return $this->cache[$thingToLoad];
    }
}
