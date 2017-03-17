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
     * @var string
     */
    private $extension;

    /**
     * @param LoaderInterface $loader
     * @param string $extension
     */
    public function __construct(LoaderInterface $loader, $extension)
    {
        $this->loader = $loader;
        $this->extension = $extension;
    }

    /**
     * Load the given directory
     *
     * @param string $directory
     * @return array
     */
    public function load($directory)
    {
        $globPattern = sprintf('%s/*%s', rtrim($directory, '/'), $this->extension);

        $phpFiles = glob($globPattern);

        $loadedFiles = array_map([$this->loader, 'load'], $phpFiles);

        return array_merge(...$loadedFiles);
    }
}
