<?php declare(strict_types=1);

namespace Imjoehaines\Flowder;

use Imjoehaines\Flowder\Loader\LoaderInterface;
use Imjoehaines\Flowder\Persister\PersisterInterface;
use Imjoehaines\Flowder\Truncator\TruncatorInterface;

final class Flowder
{
    /**
     * @param LoaderInterface $loader
     * @param TruncatorInterface $truncator
     * @param PersisterInterface $persister
     */
    public function __construct(
        LoaderInterface $loader,
        TruncatorInterface $truncator,
        PersisterInterface $persister
    ) {
        $this->loader = $loader;
        $this->truncator = $truncator;
        $this->persister = $persister;
    }

    /**
     * @param mixed $thingToLoad
     * @return void
     */
    public function loadFixtures($thingToLoad): void
    {
        $data = $this->loader->load($thingToLoad);

        foreach ($data as $table => $tableData) {
            $this->truncator->truncate($table);

            if (!empty($tableData)) {
                $this->persister->persist($table, $tableData);
            }
        }
    }
}
