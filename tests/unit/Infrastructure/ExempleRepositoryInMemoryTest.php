<?php

namespace DoctrineTestingTools\Tests\Infrastructure;

use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use DoctrineTestingTools\Infrastructure\ExempleRepositoryInMemory;
use PHPUnit\Framework\TestCase;

class ExempleRepositoryInMemoryTest extends TestCase
{
    public function testAddAndFind(): void
    {
        $exemple = new Exemple(new ExempleId("first_to_be_added"));
        $repository = new ExempleRepositoryInMemory();
        $repository->add($exemple);

        $found = $repository->findById(new ExempleId("first_to_be_added"));
        $this->assertSame($exemple, $found);
    }
}
