<?php

namespace Imjoehaines\Flowder\Loader;

final class DirectoryLoader implements LoaderInterface
{
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
     * Load the given directory
     *
     * @param string $directory
     * @return array
     */
    public function load($directory)
    {
        $phpFiles = glob(rtrim($directory, '/') . '/*.php');

        $loadedFiles = array_map([$this->loader, 'load'], $phpFiles);

        return array_merge(...$loadedFiles);
    }
}
