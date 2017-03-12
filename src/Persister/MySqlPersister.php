<?php

namespace Imjoehaines\Flowder\Persister;

final class MySqlPersister extends PdoPersister
{
    /**
     * @return void
     */
    protected function disableForeignKeys()
    {
        $this->db->exec('SET foreign_key_checks = 0');
    }

    /**
     * @return void
     */
    protected function enableForeignKeys()
    {
        $this->db->exec('SET foreign_key_checks = 1');
    }
}
