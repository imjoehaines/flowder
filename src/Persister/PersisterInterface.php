<?php

namespace Imjoehaines\Flowder\Persister;

use PDO;

interface PersisterInterface
{
    /**
     * @param PDO $db
     */
    public function __construct(PDO $db);

    /**
     * Persist an array of data
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function persist($table, array $data);
}
