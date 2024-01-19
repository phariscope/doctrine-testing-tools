<?php

namespace DoctrineTestingTools\Domain;

class Exemple
{
    public function __construct(
        private ExempleId $exempleId = new ExempleId()
    ) {
    }

    public function getExempleId(): ExempleId
    {
        return $this->exempleId;
    }
}
