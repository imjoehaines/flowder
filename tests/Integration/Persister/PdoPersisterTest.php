<?php

namespace Imjoehaines\Flowder\Test\Integration\Persister;

use PDO;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Persister\PdoPersister;

class PdoPersisterTest extends TestCase
{
    public function testItRunsOneInsertForASingleRowOfData()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('CREATE TABLE IF NOT EXISTS test_table (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $persister = new PdoPersister($db);

        $actual = $persister->persist('test_table', [
            [
                'column1' => 1,
                'column2' => 2,
                'column3' => 'three',
            ],
        ]);

        $this->assertTrue($actual);

        $statement = $db->prepare('SELECT * FROM test_table');
        $statement->execute();
        $actual = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertSame(
            [
                [
                    'column1' => '1',
                    'column2' => '2',
                    'column3' => 'three',
                ],
            ],
            $actual
        );
    }

    public function testItRunsOneInsertForMultipleRowsOfData()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('CREATE TABLE IF NOT EXISTS test_table (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $persister = new PdoPersister($db);

        $actual = $persister->persist('test_table', [
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
        ]);

        $this->assertTrue($actual);

        $statement = $db->prepare('SELECT * FROM test_table');
        $statement->execute();
        $actual = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertSame(
            [
                [
                    'column1' => '1',
                    'column2' => '2',
                    'column3' => 'three',
                ],
                [
                    'column1' => '4',
                    'column2' => '5',
                    'column3' => 'six',
                ],
                [
                    'column1' => '7',
                    'column2' => '8',
                    'column3' => 'nine',
                ],
            ],
            $actual
        );
    }
}
