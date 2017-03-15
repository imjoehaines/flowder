<?php

namespace Imjoehaines\Flowder\Test\Integration\Loader;

use PDO;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Loader\FileLoader;
use Imjoehaines\Flowder\Loader\DirectoryLoader;

class DirectoryLoaderTest extends TestCase
{
    public function testItLoadsFixturesFromAGivenDirectory()
    {
        $expected = [
            'test_data_1' => [
                [
                    'column1' => 1,
                    'column2' => 2,
                    'column3' => 'three',
                ],
            ],
            'test_data_2' => [
                [
                    'column4' => 4,
                    'column5' => 5,
                    'column6' => 'six',
                ],
            ],
            'test_data_3' => [
                [
                    'column7' => 7,
                    'column8' => 8,
                    'column9' => 'nine',
                ],
            ],
        ];

        $loader = new DirectoryLoader(new FileLoader());
        $actual = $loader->load(__DIR__ . '/../../data/directory_loader_test/');

        $this->assertSame($expected, $actual);
    }

    public function testItLoadsFixturesFromAGivenDirectoryWithoutTrailingSlash()
    {
        $expected = [
            'test_data_1' => [
                [
                    'column1' => 1,
                    'column2' => 2,
                    'column3' => 'three',
                ],
            ],
            'test_data_2' => [
                [
                    'column4' => 4,
                    'column5' => 5,
                    'column6' => 'six',
                ],
            ],
            'test_data_3' => [
                [
                    'column7' => 7,
                    'column8' => 8,
                    'column9' => 'nine',
                ],
            ],
        ];

        $loader = new DirectoryLoader(new FileLoader());
        $actual = $loader->load(__DIR__ . '/../../data/directory_loader_test');

        $this->assertSame($expected, $actual);
    }
}
