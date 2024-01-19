<?php

namespace DoctrineTestingTools\Domain;

interface ExempleRepositoryInterface
{
    public function add(Exemple $exemple): void;

    /** @throws ExempleNotFoundException */
    public function findById(ExempleId $exempleId): Exemple;
}
