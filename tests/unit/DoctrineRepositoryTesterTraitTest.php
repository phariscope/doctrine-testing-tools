<?php

namespace DoctrineTestingTools\Tests;

use Doctrine\ORM\EntityManager;
use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use DoctrineTestingTools\Domain\ExempleNotFoundException;
use DoctrineTestingTools\Infrastructure\ExempleRepositoryDoctrine;
use DoctrineTestingTools\ShouldNotDropDatabaseInProdException;
use PHPUnit\Framework\TestCase;

class DoctrineRepositoryTesterTraitTest extends TestCase
{
    use DoctrineRepositoryTesterTrait;

    private string $envKernelClass;
    private string $envAppEnv;

    public function setUp(): void
    {
        $this->envKernelClass = $_ENV["KERNEL_CLASS"];
        $this->envAppEnv = $_ENV["APP_ENV"];
        $this->initDoctrineTester();
        $this->resetDatabase();
    }

    public function tearDown(): void
    {
        $_ENV["KERNEL_CLASS"] = $this->envKernelClass;
        $_ENV["APP_ENV"] = $this->envAppEnv;
    }

    public function testKernel(): void
    {
        $this->assertEquals("test", $this->myKernel->getEnvironment());
        $this->assertEquals(false, $this->myKernel->isDebug());
    }

    public function testGetEntityManager(): void
    {
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

    public function testKernelClassIsNotSet(): void
    {
        unset($_ENV["KERNEL_CLASS"]);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("You must set the KERNEL_CLASS environment variable.");
        $this->initDoctrineTester();
    }

    public function testKernelClassIsWronglyDefined(): void
    {
        $_ENV["KERNEL_CLASS"] = "";

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(
            "Class \"\" doesn't exist or cannot be autoloaded. Check the KERNEL_CLASS value."
        );
        $this->initDoctrineTester();
    }

    public function testResetDatabase(): void
    {
        $repository = new ExempleRepositoryDoctrine($this->getEntityManager());
        $repository->add($exemple = new Exemple($id = new ExempleId("exemple")));
        $this->clearCacheForObject($exemple);

        $found = $repository->findById($id);
        $this->assertInstanceOf(Exemple::class, $found);
        $this->clearCacheForObject($found);

        $this->resetDatabase();

        $this->expectException(ExempleNotFoundException::class);
        $notfound = $repository->findById($id);
    }

    public function testResetDatabaseFailureForProdEnv(): void
    {
        $_ENV["APP_ENV"] = "prod";

        $repository = new ExempleRepositoryDoctrine($this->getEntityManager());
        $repository->add($exemple = new Exemple($id = new ExempleId("exemple")));
        $this->clearCacheForObject($exemple);

        $found = $repository->findById($id);
        $this->assertInstanceOf(Exemple::class, $found);
        $this->clearCacheForObject($found);

        $this->expectException(ShouldNotDropDatabaseInProdException::class);
        $this->expectExceptionMessage("You should not drop the database when in 'prod' environment");

        $this->resetDatabase();
    }

    public function testResetDatabaseFailureForNoEnv(): void
    {
        unset($_ENV["APP_ENV"]);

        $repository = new ExempleRepositoryDoctrine($this->getEntityManager());
        $repository->add($exemple = new Exemple($id = new ExempleId("exemple")));
        $this->clearCacheForObject($exemple);

        $found = $repository->findById($id);
        $this->assertInstanceOf(Exemple::class, $found);
        $this->clearCacheForObject($found);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("You must set the APP_ENV environment variable.");

        $this->resetDatabase();
    }
}
