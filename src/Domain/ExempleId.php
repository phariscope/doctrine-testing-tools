<?php

namespace DoctrineTestingTools\Domain;

class ExempleId
{
    private string $id;

    public function __construct(
        ?string $id = null
    ) {
        if ($id == null) {
            $this->id = uniqid();
        } else {
            $this->id = $id;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function equals(self $other): bool
    {
        return $this->getId() == $other->getId();
    }

    public function __toString(): string
    {
        return $this->getId();
    }
}
