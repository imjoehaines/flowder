<?php

namespace Imjoehaines\Flowder\Test\Integration\Persister;

use PDO;
use PHPUnit\Framework\TestCase;
use Imjoehaines\Flowder\Loader;
use Imjoehaines\Flowder\Persister\PdoPersister;

class LoaderTest extends TestCase
{
    public function testItLoadsFixturesFromAGivenFile()
    {
        $db = new PDO('sqlite::memory:');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec('CREATE TABLE IF NOT EXISTS loader_test_data (
            column1 INT PRIMARY KEY,
            column2 INT,
            column3 TEXT
        )');

        $persister = new PdoPersister($db);

        $loader = new Loader($persister);

        $loader->load(__DIR__ . '/../data/loader_test_data.php');

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
}
