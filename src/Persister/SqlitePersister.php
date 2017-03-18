<?php

namespace Imjoehaines\Flowder\Persister;

use PDO;
use Exception;

final class SqlitePersister implements PersisterInterface
{
    /**
     * The SQLite internal value for the maximum number of parameters that can
     * be used in a single query
     *
     * @see https://www.sqlite.org/c3ref/c_limit_attached.html
     * @var int
     */
    const SQLITE_LIMIT_VARIABLE_NUMBER = 9;

    /**
     * @var PDO
     */
    protected $db;

    /**
     * @param PDO $db
     */
    final public function __construct(PDO $db)
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
