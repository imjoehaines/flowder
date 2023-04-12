<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Test\Unit\Truncator;

use Imjoehaines\Flowder\Truncator\MySqlTruncator;
use PDO;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class MySqlTruncatorTest extends TestCase
{
    use ProphecyTrait;

    public function testItTruncatesAGivenTable(): void
    {
        $db = $this->prophesize(PDO::class);

        $db->exec('SET foreign_key_checks = 0')->shouldBeCalled();
        $db->exec('TRUNCATE TABLE `test_truncate_table`')->shouldBeCalled();
        $db->exec('SET foreign_key_checks = 1')->shouldBeCalled();

        $truncator = new MySqlTruncator($db->reveal());
        $truncator->truncate('test_truncate_table');
    }
}
