<?php

namespace Imjoehaines\Flowder\Test\Integration\Truncator;

use PDO;
use Prophecy\Prophet;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Truncator\MySqlTruncator;

class MySqlTruncatorTest extends TestCase
{
    public function testItTruncatesAGivenTable()
    {
        $prophet = new Prophet();

        $db = $prophet->prophesize(PDO::class);
        $db->exec('TRUNCATE TABLE `test_truncate_table`')->shouldBeCalled();

        $truncator = new MySqlTruncator($db->reveal());
        $truncator->truncate('test_truncate_table');

        $prophet->checkPredictions();
    }
}
