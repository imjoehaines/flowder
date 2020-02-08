<?php declare(strict_types=1);

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
     * @return iterable
     */
    public function load($thingToLoad): iterable
    {
        if (!array_key_exists($thingToLoad, $this->cache)) {
            foreach ($this->loader->load($thingToLoad) as $table => $data) {
                $this->cache[$thingToLoad][$table] = $data;

                yield $table => $data;
            }
        } else {
            yield from $this->cache[$thingToLoad];
        }
    }
}
