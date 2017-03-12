<?php

namespace Imjoehaines\Flowder\Loader;

final class CachingLoader implements LoaderInterface
{
    /**
     * The array of cached data
     *
     * @var array
     */
    private $cache = [];

    /**
     * A "real" loader instance that actually does the loading
     *
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Load the given thing and cache the results for repeated calls
     *
     * @param mixed $thingToLoad
     * @return array
     */
    public function load($thingToLoad)
    {
        if (empty($this->cache[$thingToLoad])) {
            $this->cache[$thingToLoad] = $this->loader->load($thingToLoad);
        }

        return $this->cache[$thingToLoad];
    }
}
