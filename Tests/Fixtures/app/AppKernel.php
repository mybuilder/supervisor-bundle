<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests\Fixtures\app;

use Doctrine\Common\Annotations\AnnotationRegistry;
use MyBuilder\Bundle\SupervisorBundle\MyBuilderSupervisorBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
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
