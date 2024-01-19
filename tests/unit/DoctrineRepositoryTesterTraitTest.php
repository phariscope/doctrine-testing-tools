<?php

namespace DoctrineTestingTools\Tests;

use Doctrine\ORM\EntityManager;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use DoctrineTestingTools\Domain\ExempleNotFoundException;
use DoctrineTestingTools\Infrastructure\ExempleRepositoryDoctrine;
use PHPUnit\Framework\TestCase;

class DoctrineRepositoryTesterTraitTest extends TestCase
{
    use DoctrineRepositoryTesterTrait;

    public function testKernel(): void
    {
        $this->initKernel();

        $this->assertEquals("test", $this->kernel->getEnvironment());
        $this->assertEquals(false, $this->kernel->isDebug());
    }

    public function testGetEntityManager(): void
    {
        $this->initKernel();
        
        $em = $this->getEntityManager();
        $this->assertInstanceOf(EntityManager::class, $em);
    }

    public function testClearTables(): void
    {
        $repository = new ExempleRepositoryDoctrine($this->getEntityManager());
        $repository->add($exemple = new Exemple($id = new ExempleId("exemple")));
        $this->clearCacheForObject($exemple);

        $found = $repository->findById($id);
        $this->assertInstanceOf(Exemple::class, $found);
        $this->clearCacheForObject($found);

        $this->clearTables(["exemples"]);

        $this->expectException(ExempleNotFoundException::class);
        $notfound = $repository->findById($id);
    }

    private function clearCacheForObject(Exemple $exemple): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->detach($exemple);
    }
}
