<?php declare(strict_types=1);

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
     * @return iterable
     */
    public function load($directory): iterable
    {
        foreach (glob($directory . '/*') as $file) {
            yield from $this->loader->load($file);
        }
    }
}
