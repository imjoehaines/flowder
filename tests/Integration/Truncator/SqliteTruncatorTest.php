<?php

namespace Imjoehaines\Flowder\Test\Integration\Truncator;

use PDO;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Truncator\SqliteTruncator;

class SqliteTruncatorTest extends TestCase
{
    public function testItTruncatesAGivenTable()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('CREATE TABLE IF NOT EXISTS test_truncate_table (
            column1 INT PRIMARY KEY
        )');

        $statement = $db->prepare('INSERT INTO test_truncate_table VALUES (1), (2), (3)');
        $statement->execute();

        $statement = $db->prepare('SELECT * FROM test_truncate_table');
        $statement->execute();
        $actualBefore = $statement->fetchAll(PDO::FETCH_ASSOC);

        $truncator = new SqliteTruncator($db);
        $truncator->truncate('test_truncate_table');

        $actualAfter = $statement->fetchAll(PDO::FETCH_ASSOC);

        $expectedBefore = [['column1' => '1'], ['column1' => '2'], ['column1' => '3']];
        $expectedAfter = [];

        $this->assertSame($expectedBefore, $actualBefore);
        $this->assertSame($expectedAfter, $actualAfter);
    }

    public function testItResetsTheAutoIncrementValueOfTheTable()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('CREATE TABLE IF NOT EXISTS test_truncate_table (
            column1 INTEGER PRIMARY KEY AUTOINCREMENT,
            column2 TEXT
        )');

        $statement = $db->prepare('INSERT INTO test_truncate_table VALUES (null, "a"), (null, "b"), (null, "c")');
        $statement->execute();

        $statement = $db->prepare('SELECT * FROM test_truncate_table');
        $statement->execute();
        $actualBefore = $statement->fetchAll(PDO::FETCH_ASSOC);

        $truncator = new SqliteTruncator($db);
        $truncator->truncate('test_truncate_table');

        $expectedBefore = [
            ['column1' => '1', 'column2' => 'a'],
            ['column1' => '2', 'column2' => 'b'],
            ['column1' => '3', 'column2' => 'c'],
        ];

        $expectedAfter = [['column1' => '1', 'column2' => 'z']];

        $statement = $db->prepare('INSERT INTO test_truncate_table VALUES (null, "z")');
        $statement->execute();

        $statement = $db->prepare('SELECT * FROM test_truncate_table');
        $statement->execute();
        $actualAfter = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertSame($expectedBefore, $actualBefore);
        $this->assertSame($expectedAfter, $actualAfter);
    }
}
