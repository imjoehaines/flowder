<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Test\Integration\Loader;

use Imjoehaines\Flowder\Loader\PhpFileLoader;
use PHPUnit\Framework\TestCase;
use Traversable;

final class PhpFileLoaderTest extends TestCase
{
    public function testItLoadsFixturesFromAGivenFile(): void
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

        $this->assertInstanceOf(Traversable::class, $actual);
        $this->assertSame($expected, iterator_to_array($actual));
    }
}
