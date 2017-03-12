<?php

namespace Imjoehaines\Flowder\Persister;

use PDO;

abstract class PdoPersister implements PersisterInterface
{
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
     * @param string $table
     * @param array $data multidimensional in the format `[['column' => 'value'], ...]`
     * @return void
     */
    final public function persist($table, array $data)
    {
        $columns = array_keys(reset($data));

        $rowPlaceholders = implode(', ', array_fill(0, count($columns), '?'));

        $placeholders = implode('), (', array_fill(0, count($data), $rowPlaceholders));

        $query = sprintf(
            'INSERT INTO `%s` (`%s`)
                  VALUES (%s)',
            $table,
            implode('`, `', $columns),
            $placeholders
        );

        $values = array_merge(...array_map('array_values', $data));

        $this->disableForeignKeys($table);

        $statement = $this->db->prepare($query);
        $statement->execute($values);

        $this->enableForeignKeys($table);
    }

    /**
     * @param string $table
     * @return void
     */
    abstract protected function disableForeignKeys($table);

    /**
     * @param string $table
     * @return void
     */
    abstract protected function enableForeignKeys($table);
}
