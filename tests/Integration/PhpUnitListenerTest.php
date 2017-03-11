<?php

namespace Imjoehaines\Flowder\Test\Integration;

use PDO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\PhpUnitListener;
use Imjoehaines\Flowder\Loader\FileLoader;
use Imjoehaines\Flowder\Loader\DirectoryLoader;
use Imjoehaines\Flowder\Persister\PdoPersister;
use Imjoehaines\Flowder\Truncator\SqliteTruncator;

class PhpUnitListenerTest extends TestCase
{
    public function testItLoadsFixturesFromAFileIfGivenThePathToAFile()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('CREATE TABLE IF NOT EXISTS loader_test_data (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $truncator = new SqliteTruncator($db);
        $loader = new FileLoader();
        $persister = new PdoPersister($db);

        $listener = new PhpUnitListener(
            __DIR__ . '/../data/loader_test_data.php',
            $truncator,
            $loader,
            $persister
        );

        $listener->startTest($this);

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

    public function testItTruncatesDataBeforeInsertingAgain()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('CREATE TABLE IF NOT EXISTS loader_test_data (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $truncator = new SqliteTruncator($db);
        $loader = new FileLoader();
        $persister = new PdoPersister($db);

        $listener = new PhpUnitListener(
            __DIR__ . '/../data/loader_test_data.php',
            $truncator,
            $loader,
            $persister
        );

        $listener->startTest($this);

        // call start test multiple times to check that we can re-insert the same data
        // without getting primary key clashes
        $listener->startTest($this);
        $listener->startTest($this);

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

    public function testItLoadsFixturesFromADirectoryIfGivenThePathToADirectory()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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


        $truncator = new SqliteTruncator($db);
        $loader = new DirectoryLoader();
        $persister = new PdoPersister($db);

        $listener = new PhpUnitListener(
            __DIR__ . '/../data/directory_loader_test',
            $truncator,
            $loader,
            $persister
        );

        $listener->startTest($this);

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
