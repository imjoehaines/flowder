<?php

namespace Imjoehaines\Flowder\Truncator;

use PDO;

class SqliteTruncator implements TruncatorInterface
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function truncate($table)
    {
        $this->db->exec('DELETE FROM `' . $table . '`');

        // reset the auto-increment value only if the sqlite_sequence table exists
        $statement = $this->db->prepare(
            'SELECT 1
               FROM sqlite_master
              WHERE type = "table"
                AND name = "sqlite_sequence"'
        );

        $statement->execute();
        $exists = $statement->fetchColumn();

        if ($exists === '1') {
            $statement = $this->db->prepare('DELETE FROM sqlite_sequence WHERE name = :table');
            $statement->execute(['table' => $table]);
        }
    }
}
