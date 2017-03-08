<?php

namespace Imjoehaines\Flowder\Test\Unit\Persister;

use PDO;
use PDOStatement;
use Prophecy\Prophet;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Persister\MySqlPersister;

class MySqlPersisterTest extends TestCase
{
    public function testItRunsOneInsertForASingleRowOfData()
    {
        $prophet = new Prophet();
        $db = $prophet->prophesize(PDO::class);
        $statement = $prophet->prophesize(PDOStatement::class);

        $db->prepare(
            'INSERT INTO `table_name` (`column1`, `column2`)
                  VALUES (?, ?)'
        )->shouldBeCalled()->willReturn($statement->reveal());

        $statement->execute([
            'value 1',
            'value 2',
        ])->shouldBeCalled()->willReturn(true);

        $persister = new MySqlPersister($db->reveal());

        $actual = $persister->persist('table_name', [
            [
                'column1' => 'value 1',
                'column2' => 'value 2',
            ],
        ]);

        $prophet->checkPredictions();

        $this->assertTrue($actual);
    }

    public function testItRunsOneInsertForMultipleRowsOfData()
    {
        $prophet = new Prophet();
        $db = $prophet->prophesize(PDO::class);
        $statement = $prophet->prophesize(PDOStatement::class);

        $db->prepare(
            'INSERT INTO `table_name` (`column1`, `column2`)
                  VALUES (?, ?), (?, ?), (?, ?), (?, ?)'
        )->shouldBeCalled()->willReturn($statement->reveal());

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

        $actual = $persister->persist('table_name', [
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

        $prophet->checkPredictions();

        $this->assertTrue($actual);
    }
}
