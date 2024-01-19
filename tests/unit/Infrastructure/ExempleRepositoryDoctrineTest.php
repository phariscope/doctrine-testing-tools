<?php

namespace DoctrineTestingTools\Tests\Infrastructure;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use DoctrineTestingTools\Infrastructure\ExempleRepositoryDoctrine;

class ExempleRepositoryDoctrineTest extends ExempleRepositoryTestBase
{
    use DoctrineRepositoryTesterTrait;

    protected function createRepository(): void
    {
        $this->repository = new ExempleRepositoryDoctrine($this->getEntityManager());
    }
}
