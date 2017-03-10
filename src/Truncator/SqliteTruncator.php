<?php

namespace Imjoehaines\Flowder\Truncator;

use PDO;

class SqliteTruncator
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function truncate($table)
    {
        $this->db->exec('DELETE FROM `' . $table . '`');

        // reset the auto-increment value
        $this->db->exec('DELETE FROM SQLITE_SEQUENCE WHERE name = `' . $table . '`');
    }
}
