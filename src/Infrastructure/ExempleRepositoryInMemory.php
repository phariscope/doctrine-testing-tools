<?php

namespace DoctrineTestingTools\Infrastructure;

use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use DoctrineTestingTools\Domain\ExempleNotFoundException;
use DoctrineTestingTools\Domain\ExempleRepositoryInterface;

class ExempleRepositoryInMemory implements ExempleRepositoryInterface
{
    /** @var array<string,Exemple> $exemples */
    private array $exemples = [];

    public function add(Exemple $exemple): void
    {
        $this->exemples[$exemple->getExempleId()->getId()] = $exemple;
    }

    /** @throws ExempleNotFoundException */
    public function findById(ExempleId $exempleId): Exemple
    {
        if (key_exists($exempleId->getId(), $this->exemples)) {
            return $this->exemples[$exempleId->getId()];
        }
        throw new ExempleNotFoundException();
    }
}
