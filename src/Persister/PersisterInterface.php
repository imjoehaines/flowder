<?php declare(strict_types=1);

namespace Imjoehaines\Flowder\Persister;

interface PersisterInterface
{
    /**
     * Persist an array of data
     *
     * @param string $table
     * @param array $data
     * @return void
     */
    public function persist(string $table, array $data): void;
}
