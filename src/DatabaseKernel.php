<?php

namespace DoctrineTestingTools;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;

class DatabaseKernel extends Kernel
{
    use MicroKernelTrait;
}
