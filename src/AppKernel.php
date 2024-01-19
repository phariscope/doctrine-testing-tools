<?php

namespace DoctrineTestingTools;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    use MicroKernelTrait;
}
