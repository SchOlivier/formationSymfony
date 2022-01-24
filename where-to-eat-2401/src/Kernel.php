<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // public function getCacheDir(): string
    // {
    //     return '/tmp/cache/' . $this->getEnvironment();
    // }
    // public function getLogDir(): string
    // {
    //     return '/tmp/log/';
    // }
}
