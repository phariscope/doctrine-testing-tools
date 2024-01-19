<?php

namespace DoctrineTestingTools\Tests\Infrastructure;

use DoctrineTestingTools\Infrastructure\ExempleRepositoryInMemory;

class ExempleRepositoryInMemoryTest extends ExempleRepositoryTestBase
{
    protected function createRepository(): void
    {
        $this->repository = new ExempleRepositoryInMemory();
    }
}
