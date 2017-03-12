<?php

namespace Imjoehaines\Flowder\Truncator;

use PDO;

class MySqlTruncator implements TruncatorInterface
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
     * Truncate the given table
     *
     * @param string $table
     * @return void
     */
    public function truncate($table)
    {
        $this->db->exec('SET foreign_key_checks = 0');
        $this->db->exec('TRUNCATE TABLE `' . $table . '`');
        $this->db->exec('SET foreign_key_checks = 1');
    }
}
