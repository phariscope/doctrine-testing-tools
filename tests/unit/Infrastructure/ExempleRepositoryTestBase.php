<?php

namespace DoctrineTestingTools\Tests\Infrastructure;

use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use DoctrineTestingTools\Domain\ExempleNotFoundException;
use DoctrineTestingTools\Domain\ExempleRepositoryInterface;
use PHPUnit\Framework\TestCase;

abstract class ExempleRepositoryTestBase extends TestCase
{
    protected ExempleRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->createRepository();
    }

    abstract protected function createRepository(): void;

    public function testAddAndFind(): void
    {
        $exemple = new Exemple(new ExempleId("first_to_be_added"));
        $this->repository->add($exemple);

        $found = $this->repository->findById(new ExempleId("first_to_be_added"));
        $this->assertEquals($exemple, $found);
    }

    public function testFindFailure(): void
    {
        $this->expectException(ExempleNotFoundException::class);

        $this->repository->findById(new ExempleId("first_to_be_added"));
    }
}
