<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Test\Integration\Persister;

use Imjoehaines\Flowder\Persister\SqlitePersister;
use PDO;
use PHPUnit\Framework\TestCase;

final class SqlitePersisterTest extends TestCase
{
    private function getDatabaseConnection(): PDO
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);

        return $db;
    }

    public function testItRunsOneInsertForASingleRowOfData(): void
    {
        $db = $this->getDatabaseConnection();

        $db->exec('CREATE TABLE IF NOT EXISTS test_table (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $persister = new SqlitePersister($db);

        $persister->persist('test_table', [
            [
                'column1' => 1,
                'column2' => 2,
                'column3' => 'three',
            ],
        ]);

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

    public function testItRunsOneInsertForMultipleRowsOfData(): void
    {
        $db = $this->getDatabaseConnection();

        $db->exec('CREATE TABLE IF NOT EXISTS test_table (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $persister = new SqlitePersister($db);

        $persister->persist('test_table', [
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

    public function testItCanHandleBrokenForeignKeys(): void
    {
        $db = $this->getDatabaseConnection();

        $db->exec('PRAGMA foreign_keys = ON');

        $db->exec('CREATE TABLE IF NOT EXISTS test_table (
            id INT PRIMARY KEY
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS test_table_2 (
            id INT PRIMARY KEY,
            test_table_id INT,
            FOREIGN KEY(test_table_id) REFERENCES test_table(id)
        )');

        $persister = new SqlitePersister($db);

        $persister->persist('test_table_2', [
            [
                'id' => 1,
                'test_table_id' => 1,
            ],
        ]);

        $statement = $db->prepare('SELECT * FROM test_table_2');
        $statement->execute();
        $actual = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertSame(
            [
                [
                    'id' => '1',
                    'test_table_id' => '1',
                ],
            ],
            $actual
        );
    }
}
