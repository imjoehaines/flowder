<?php

namespace Imjoehaines\Flowder\Persister;

use PDO;

interface PersisterInterface
{
    /**
     * Persist an array of data
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function persist($table, array $data);
}
