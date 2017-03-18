<?php

namespace Imjoehaines\Flowder\Persister;

use PDO;
use Exception;

final class SqlitePersister implements PersisterInterface
{
    /**
     * @var PDO
     */
    protected $db;

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Persist an array of data
     *
     * Because of SQLite's SQLITE_MAX_VARIABLE_NUMBER limitation, we do this as
     * a separate insert per row of data inside a transaction
     *
     * @param string $table
     * @param array $data multidimensional in the format `[['column' => 'value'], ...]`
     * @return void
     */
    public function persist($table, array $data)
    {
        $columns = array_keys(reset($data));
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));

        $query = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table,
            implode('`, `', $columns),
            $placeholders
        );

        $values = array_map('array_values', $data);

        try {
            $this->db->exec('PRAGMA foreign_keys = OFF');
            $this->db->exec('BEGIN TRANSACTION');

            $statement = $this->db->prepare($query);

            foreach ($values as $row) {
                $statement->execute($row);
            }

            $this->db->exec('COMMIT TRANSACTION');
            $this->db->exec('PRAGMA foreign_keys = ON');
        } catch (Exception $e) {
            $this->db->exec('ROLLBACK');

            throw $e;
        }
    }
}
