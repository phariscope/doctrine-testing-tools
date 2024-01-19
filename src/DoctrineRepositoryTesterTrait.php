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

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->initKernel();
    }

    private function initKernel()
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
        $class = $this->getKernelClass();
        $kernel = new $class(self::KERNEL_ENV, self::KERNEL_DEBUG_VALUE);

        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->run(new StringInput($command . ' --quiet'));
    }

    /**
     * @throws \RuntimeException
     * @throws \LogicException
     */
    private function getKernelClass(): string
    {
        if (!isset($_SERVER['KERNEL_CLASS']) && !isset($_ENV['KERNEL_CLASS'])) {
            throw new \LogicException(sprintf('You must set the KERNEL_CLASS environment variable.', static::class));
        }

        if (!class_exists($class = $_ENV['KERNEL_CLASS'] ?? $_SERVER['KERNEL_CLASS'])) {
            throw new \RuntimeException(
                sprintf(
                    'Class "%s" doesn\'t exist or cannot be autoloaded. Check that the KERNEL_CLASS value.',
                    $class,
                    static::class
                )
            );
        }

        return $class;
    }
}
