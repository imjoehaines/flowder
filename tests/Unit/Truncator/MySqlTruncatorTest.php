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
        $db = $this->prophesize(PDO::class);

        $db->exec('SET foreign_key_checks = 0')->shouldBeCalled();
        $db->exec('TRUNCATE TABLE `test_truncate_table`')->shouldBeCalled();
        $db->exec('SET foreign_key_checks = 1')->shouldBeCalled();

        $truncator = new MySqlTruncator($db->reveal());
        $truncator->truncate('test_truncate_table');
    }
}
