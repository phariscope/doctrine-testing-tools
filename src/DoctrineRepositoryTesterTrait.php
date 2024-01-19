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

    private Kernel $kernel;
    private Application $app;

    private function initDoctrineTester()
    {
        $class = $this->getKernelClass();
        $this->kernel = new $class(self::KERNEL_ENV, self::KERNEL_DEBUG_VALUE);
        $this->kernel->boot();

        $this->app = new Application($this->kernel);
        $this->app->setAutoExit(false);
    }

    private function getEntityManager(): EntityManagerInterface
    {
        /** @var EntityManagerInterface $em */
        $em = $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
        return $em;
    }

    /** @param array<string> $tables */
    private function clearTables(array $tables): void
    {
        /** @var string $table */
        foreach ($tables as $table) {
            $this->runCommand(sprintf('doctrine:query:sql "DROP TABLE IF EXISTS %s"', $table));
        }
        $this->runCommand('doctrine:schema:update --complete --force --dump-sql');
    }

    private function runCommand(string $command): void
    {
        $this->app->run(new StringInput(sprintf('%s --quiet', $command)));
    }

    /**
     * @throws \RuntimeException
     * @throws \LogicException
     */
    private function getKernelClass(): string
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
