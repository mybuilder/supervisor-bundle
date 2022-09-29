<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests\Fixtures\app;

use MyBuilder\Bundle\SupervisorBundle\MyBuilderSupervisorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new MyBuilderSupervisorBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/' . $this->getEnvironment() . '.yml');
    }
}
