<?php

namespace Imjoehaines\Flowder\Test\Integration\Loader;

use PDO;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Loader\PhpFileLoader;

class PhpFileLoaderTest extends TestCase
{
    public function testItLoadsFixturesFromAGivenFile()
    {
        $expected = [
            'loader_test_data' => [
                [
                    'column1' => 1,
                    'column2' => 2,
                    'column3' => 'three',
                ],
                [
                    'column1' => 4,
                    'column2' => 5,
                    'column3' => 'six',
                ],
                [
                    'column1' => 7,
                    'column2' => 8,
                    'column3' => 'nine',
                ],
            ],
        ];

        $loader = new PhpFileLoader();
        $actual = $loader->load(__DIR__ . '/../../data/loader_test_data.php');

        $this->assertSame($expected, iterator_to_array($actual));
    }
}