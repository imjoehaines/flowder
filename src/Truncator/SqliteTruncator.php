<?php

declare(strict_types=1);

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
     * Delete all data from the given table
     *
     * @param string $table
     * @return void
     */
    public function truncate(string $table): void
    {
        $this->db->exec('PRAGMA foreign_keys = OFF');
        $this->db->exec('DELETE FROM `' . $table . '`');
        $this->db->exec('PRAGMA foreign_keys = ON');
    }
}
