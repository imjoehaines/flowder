<?php

namespace Imjoehaines\Flowder\Persister;

final class SqlitePersister extends PdoPersister
{
    /**
     * @return void
     */
    protected function disableForeignKeys()
    {
        $this->db->exec('PRAGMA foreign_keys = OFF');
    }

    /**
     * @return void
     */
    protected function enableForeignKeys()
    {
        $this->db->exec('PRAGMA foreign_keys = ON');
    }
}
