<?php

namespace Imjoehaines\Flowder\Test\Integration\Loader;

use PDO;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Loader\PhpFileLoader;
use Imjoehaines\Flowder\Loader\CachingLoader;
use Imjoehaines\Flowder\Loader\DirectoryLoader;
use Imjoehaines\Flowder\Test\FileRequireCounter;

class CachingLoaderTest extends TestCase
{
    public function testItLoadsFixturesFromAGivenFileAndCachesTheResult()
    {
        FileRequireCounter::reset();

        $expected = [
            'cache_test_data' => [
                [
                    'count' => 1,
                ],
            ],
        ];

        $this->assertSame(0, FileRequireCounter::$count);

        $fileLoader = new PhpFileLoader();
        $loader = new CachingLoader($fileLoader);

        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, iterator_to_array($actual));

        // re-load the data and check that the FileRequireCounter doesn't increment
        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, iterator_to_array($actual));

        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, iterator_to_array($actual));

        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, iterator_to_array($actual));

        $this->assertSame(1, FileRequireCounter::$count);
    }

    public function testItLoadsFixturesFromAGivenDirectoryAndCachesTheResult()
    {
        FileRequireCounter::reset();

        $expected = [
            'cache_test_data' => [
                [
                    'count' => 1,
                ],
            ],
            'cache_test_data_2' => [
                [
                    'count' => 2,
                ],
            ],
        ];

        $this->assertSame(0, FileRequireCounter::$count);

        $directoryLoader = new DirectoryLoader(new PhpFileLoader(), '.php');
        $loader = new CachingLoader($directoryLoader);

        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, iterator_to_array($actual));

        // re-load the data and check that the FileRequireCounter doesn't increment
        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, iterator_to_array($actual));

        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, iterator_to_array($actual));

        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, iterator_to_array($actual));

        $this->assertSame(2, FileRequireCounter::$count);
    }
}
