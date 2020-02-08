<?php declare(strict_types=1);

namespace Imjoehaines\Flowder\Truncator;

interface TruncatorInterface
{
    /**
     * Remove all data from the given table
     *
     * @param string $table
     * @return void
     */
    public function truncate(string $table): void;
}
