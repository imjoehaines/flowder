<?php declare(strict_types=1);

namespace Imjoehaines\Flowder\Test\Integration\Loader;

use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Loader\PhpFileLoader;
use Imjoehaines\Flowder\Loader\DirectoryLoader;

final class DirectoryLoaderTest extends TestCase
{
    public function testItLoadsFixturesFromAGivenDirectory(): void
    {
        $expected = [
            'empty' => [],
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

        $loader = new DirectoryLoader(new PhpFileLoader());
        $actual = $loader->load(__DIR__ . '/../../data/directory_loader_test/');

        $this->assertSame($expected, iterator_to_array($actual));
    }

    public function testItLoadsFixturesFromAGivenDirectoryWithoutTrailingSlash(): void
    {
        $expected = [
            'empty' => [],
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

        $loader = new DirectoryLoader(new PhpFileLoader());
        $actual = $loader->load(__DIR__ . '/../../data/directory_loader_test');

        $this->assertSame($expected, iterator_to_array($actual));
    }
}
