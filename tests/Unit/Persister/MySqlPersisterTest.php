<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Test\Unit\Persister;

use Imjoehaines\Flowder\Persister\MySqlPersister;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

final class MySqlPersisterTest extends TestCase
{
    public function testItRunsOneInsertForASingleRowOfData(): void
    {
        $db = $this->prophesize(PDO::class);
        $statement = $this->prophesize(PDOStatement::class);

        $db->exec('SET foreign_key_checks = 0')->shouldBeCalled();

        $db->prepare(
            'INSERT INTO `table_name` (`column1`, `column2`) VALUES (?, ?)'
        )->shouldBeCalled()->willReturn($statement->reveal());

        $db->exec('SET foreign_key_checks = 1')->shouldBeCalled();

        $statement->execute([
            'value 1',
            'value 2',
        ])->shouldBeCalled()->willReturn(true);

        $persister = new MySqlPersister($db->reveal());

        $persister->persist('table_name', [
            [
                'column1' => 'value 1',
                'column2' => 'value 2',
            ],
        ]);
    }

    public function testItRunsOneInsertForMultipleRowsOfData(): void
    {
        $db = $this->prophesize(PDO::class);
        $statement = $this->prophesize(PDOStatement::class);

        $db->exec('SET foreign_key_checks = 0')->shouldBeCalled();

        $db->prepare(
            'INSERT INTO `table_name` (`column1`, `column2`) VALUES (?, ?), (?, ?), (?, ?), (?, ?)'
        )->shouldBeCalled()->willReturn($statement->reveal());

        $db->exec('SET foreign_key_checks = 1')->shouldBeCalled();

        $statement->execute([
            'value 1',
            'value 2',
            'value 3',
            'value 4',
            'value 5',
            'value 6',
            'value 7',
            'value 8',
        ])->shouldBeCalled()->willReturn(true);

        $persister = new MySqlPersister($db->reveal());

        $persister->persist('table_name', [
            [
                'column1' => 'value 1',
                'column2' => 'value 2',
            ],
            [
                'column1' => 'value 3',
                'column2' => 'value 4',
            ],
            [
                'column1' => 'value 5',
                'column2' => 'value 6',
            ],
            [
                'column1' => 'value 7',
                'column2' => 'value 8',
            ],
        ]);
    }
}
