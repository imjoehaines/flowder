<?php

declare(strict_types=1);

namespace Imjoehaines\Flowder\Test\Integration;

use Imjoehaines\Flowder\Flowder;
use Imjoehaines\Flowder\Loader\DirectoryLoader;
use Imjoehaines\Flowder\Loader\PhpFileLoader;
use Imjoehaines\Flowder\Persister\SqlitePersister;
use Imjoehaines\Flowder\Truncator\SqliteTruncator;
use PDO;
use PHPUnit\Framework\TestCase;

final class FlowderTest extends TestCase
{
    private function getDatabaseConnection(): PDO
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);

        return $db;
    }

    public function testItLoadsFixturesFromAFileIfGivenThePathToAFile(): void
    {
        $db = $this->getDatabaseConnection();

        $db->exec('CREATE TABLE IF NOT EXISTS loader_test_data (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $loader = new PhpFileLoader();
        $truncator = new SqliteTruncator($db);
        $persister = new SqlitePersister($db);

        $flowder = new Flowder(
            $loader,
            $truncator,
            $persister
        );

        $flowder->loadFixtures(__DIR__ . '/../data/loader_test_data.php');

        $statement = $db->prepare('SELECT * FROM loader_test_data');
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

    public function testItTruncatesDataBeforeInsertingAgain(): void
    {
        $db = $this->getDatabaseConnection();

        $db->exec('CREATE TABLE IF NOT EXISTS loader_test_data (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $loader = new PhpFileLoader();
        $truncator = new SqliteTruncator($db);
        $persister = new SqlitePersister($db);

        $flowder = new Flowder(
            $loader,
            $truncator,
            $persister
        );

        $flowder->loadFixtures(__DIR__ . '/../data/loader_test_data.php');

        // call start test multiple times to check that we can re-insert the same data
        // without getting primary key clashes
        $flowder->loadFixtures(__DIR__ . '/../data/loader_test_data.php');
        $flowder->loadFixtures(__DIR__ . '/../data/loader_test_data.php');

        $statement = $db->prepare('SELECT * FROM loader_test_data');
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

    public function testItLoadsFixturesFromADirectoryIfGivenThePathToADirectory(): void
    {
        $db = $this->getDatabaseConnection();

        $db->exec('CREATE TABLE IF NOT EXISTS empty (column1 INT PRIMARY KEY)');

        $db->exec('CREATE TABLE IF NOT EXISTS test_data_1 (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS test_data_2 (
            column4 INT PRIMARY KEY,
            column5 INT,
            column6 TEXT
        )');

        $db->exec('CREATE TABLE IF NOT EXISTS test_data_3 (
            column7 INT PRIMARY KEY,
            column8 INT,
            column9 TEXT
        )');

        $loader = new DirectoryLoader(new PhpFileLoader());
        $truncator = new SqliteTruncator($db);
        $persister = new SqlitePersister($db);

        $flowder = new Flowder(
            $loader,
            $truncator,
            $persister
        );

        $flowder->loadFixtures(__DIR__ . '/../data/directory_loader_test');

        $statement = $db->prepare('SELECT * FROM test_data_1');
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

        $statement = $db->prepare('SELECT * FROM test_data_2');
        $statement->execute();
        $actual = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertSame(
            [
                [
                    'column4' => '4',
                    'column5' => '5',
                    'column6' => 'six',
                ],
            ],
            $actual
        );

        $statement = $db->prepare('SELECT * FROM test_data_3');
        $statement->execute();
        $actual = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertSame(
            [
                [
                    'column7' => '7',
                    'column8' => '8',
                    'column9' => 'nine',
                ],
            ],
            $actual
        );
    }
}
