<?php

namespace DoctrineTestingTools\Infrastructure;

use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use DoctrineTestingTools\Domain\ExempleRepositoryInterface;

class ExempleRepositoryInMemory implements ExempleRepositoryInterface
{
    /** @var array<string,Exemple> $exemples */
    private array $exemples = [];

    public function add(Exemple $exemple): void
    {
        $this->exemples[$exemple->getExempleId()->getId()] = $exemple;
    }

    public function findById(ExempleId $exempleId): Exemple
    {
        return $this->exemples[$exempleId->getId()];
    }
}
