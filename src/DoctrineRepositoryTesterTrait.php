<?php

namespace DoctrineTestingTools;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpKernel\Kernel;

trait DoctrineRepositoryTesterTrait
{
    private const KERNEL_ENV = "test";
    private const KERNEL_DEBUG_VALUE = false;

    private Kernel $myKernel;
    private Application $app;

    private function initDoctrineTester()
    {
        $class = $this->getDefaultKernelClass();
        $this->myKernel = new $class(self::KERNEL_ENV, self::KERNEL_DEBUG_VALUE);
        $this->myKernel->boot();

        $this->app = new Application($this->myKernel);
        $this->app->setAutoExit(false);
    }

    private function getEntityManager(): EntityManagerInterface
    {
        /** @var EntityManagerInterface $em */
        $em = $this->myKernel->getContainer()->get('doctrine.orm.entity_manager');
        return $em;
    }

    /** @param array<string> $tables */
    private function clearTables(array $tables): void
    {
        $em = $this->getEntityManager();
        /** @var string $table */
        foreach ($tables as $table) {
            $em->getConnection()->executeStatement(sprintf('DROP TABLE IF EXISTS %s;', $table));
        }
        $this->runCommand('doctrine:schema:update --complete --force');
    }

    private function resetDatabase(): void
    {
        if (!isset($_ENV['APP_ENV'])) {
            throw new \LogicException('You must set the APP_ENV environment variable.');
        }

        if ($_ENV["APP_ENV"] == "test" || $_ENV["APP_ENV"] == "dev") {
            $this->runCommand('doctrine:database:drop --force');
            $this->runCommand('doctrine:database:create');
            $this->runCommand('doctrine:schema:create');
        } else {
            throw new ShouldNotDropDatabaseInProdException(
                sprintf("You should not drop the database when in '%s' environment", $_ENV["APP_ENV"])
            );
        }
    }

    private function runCommand(string $command): void
    {
        $this->app->run(new StringInput(sprintf('%s --quiet', $command)));
    }

    /**
     * @throws \RuntimeException
     * @throws \LogicException
     */
    private function getDefaultKernelClass(): string
    {
        if (!isset($_ENV['KERNEL_CLASS'])) {
            throw new \LogicException('You must set the KERNEL_CLASS environment variable.');
        }

        if (!class_exists($class = $_ENV['KERNEL_CLASS'])) {
            throw new \RuntimeException(
                sprintf(
                    'Class "%s" doesn\'t exist or cannot be autoloaded. Check the KERNEL_CLASS value.',
                    $class,
                    static::class
                )
            );
        }

        return $class;
    }
}
