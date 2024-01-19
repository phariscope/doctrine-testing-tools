<?php

namespace DoctrineTestingTools\Domain;

interface ExempleRepositoryInterface
{
    public function add(Exemple $exemple): void;

    public function findById(ExempleId $exempleId): Exemple;
}
