<?php

namespace Imjoehaines\Flowder\Persister;

final class SqlitePersister extends PdoPersister
{
    /**
     * @param string $table
     * @return void
     */
    protected function disableForeignKeys($table)
    {
        $this->db->exec('PRAGMA foreign_keys = OFF');
    }

    /**
     * @param string $table
     * @return void
     */
    protected function enableForeignKeys($table)
    {
        $this->db->exec('PRAGMA foreign_keys = ON');
    }
}
