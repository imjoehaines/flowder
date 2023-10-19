<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Test\Unit\Persister;

use Imjoehaines\Flowder\Persister\SqlitePersister;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

final class SqlitePersisterTest extends TestCase
{
    use ProphecyTrait;

    public function testItRollsbackUponError(): void
    {
        $db = $this->prophesize(PDO::class);
        $statement = $this->prophesize(PDOStatement::class);

        $db->exec('PRAGMA foreign_keys = OFF')->shouldBeCalled();
        $db->exec('BEGIN TRANSACTION')->shouldBeCalled();

        $db->prepare(
            'INSERT INTO `table_name` (`column1`, `column2`) VALUES (?, ?)'
        )->shouldBeCalled()->willReturn($statement->reveal());

        $db->exec('COMMIT TRANSACTION')->shouldBeCalled()->willThrow(new PDOException('nope'));
        $db->exec('PRAGMA foreign_keys = ON')->shouldNotBeCalled();

        $db->exec('ROLLBACK')->shouldBeCalled();

        $statement->execute([
            'value 1',
            'value 2',
        ])->shouldBeCalled()->willReturn(true);

        $statement->execute([
            'value 3',
            'value 4',
        ])->shouldBeCalled()->willReturn(true);

        $statement->execute([
            'value 5',
            'value 6',
        ])->shouldBeCalled()->willReturn(true);

        $statement->execute([
            'value 7',
            'value 8',
        ])->shouldBeCalled()->willReturn(true);

        $this->expectExceptionObject(new PDOException('nope'));

        $persister = new SqlitePersister($db->reveal());

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
