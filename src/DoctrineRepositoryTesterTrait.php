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

    private function initDoctrineTester(): void
    {
        $class = $this->getDefaultKernelClass();
        /** @var object&Kernel $myKernel */
        // @phpstan-ignore-next-line no easy way to make phpstan understand this
        $myKernel = new $class(self::KERNEL_ENV, self::KERNEL_DEBUG_VALUE);
        
        $myKernel->boot();
        $this->myKernel = $myKernel;

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
        $this->runCommand('doctrine:schema:update --force');
    }

    private function resetDatabase(): void
    {
        /** @var array<string,string> $_ENV */
        if (!isset($_ENV['APP_ENV']) || !is_string($_ENV['APP_ENV'])) {
            throw new \LogicException('You must set the APP_ENV environment variable.');
        }
        /** @var string $appEnv */
        $appEnv = $_ENV["APP_ENV"];

        if ($appEnv == "test" || $appEnv == "dev") {
            $this->runCommand('doctrine:database:drop --force');
            $this->runCommand('doctrine:database:create');
            $this->runCommand('doctrine:schema:create');
        } else {
            throw new ShouldNotDropDatabaseInProdException(
                sprintf("You should not drop the database when in '%s' environment", $appEnv)
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
        if (!isset($_ENV['KERNEL_CLASS']) || !is_string($_ENV['KERNEL_CLASS'])) {
            throw new \LogicException('You must set the KERNEL_CLASS environment variable.');
        }

        if (!class_exists($class = $_ENV['KERNEL_CLASS'])) {
            throw new \RuntimeException(
                sprintf(
                    'Class "%s" doesn\'t exist or cannot be autoloaded. Check the KERNEL_CLASS value.',
                    $class
                )
            );
        }

        return $class;
    }
}
