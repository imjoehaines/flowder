<?php

namespace Imjoehaines\Flowder\Truncator;

use PDO;

final class SqliteTruncator implements TruncatorInterface
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Delete all data from the given table, mimicking MySQL's `TRUNCATE` by
     * resetting the auto-increment as well
     *
     * @param string $table
     * @return void
     */
    public function truncate($table)
    {
        $this->db->exec('PRAGMA foreign_keys = OFF');

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

        $this->db->exec('PRAGMA foreign_keys = ON');
    }
}
