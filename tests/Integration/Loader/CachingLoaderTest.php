<?php

namespace Imjoehaines\Flowder\Test\Integration\Loader;

use PDO;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Loader\FileLoader;
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

        $fileLoader = new FileLoader();
        $loader = new CachingLoader($fileLoader);

        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, $actual);

        // re-load the data and check that the FileRequireCounter doesn't increment
        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, $actual);

        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, $actual);

        $actual = $loader->load(__DIR__ . '/../../data/cache/cache_test_data.php');
        $this->assertSame($expected, $actual);
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

        $directoryLoader = new DirectoryLoader();
        $loader = new CachingLoader($directoryLoader);

        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, $actual);

        // re-load the data and check that the FileRequireCounter doesn't increment
        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, $actual);

        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, $actual);

        $actual = $loader->load(__DIR__ . '/../../data/cache');
        $this->assertSame($expected, $actual);
    }
}
