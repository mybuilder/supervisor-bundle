<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SupervisorTestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        require_once __DIR__.'/Fixtures/app/AppKernel.php';

        return 'MyBuilder\Bundle\SupervisorBundle\Tests\Fixtures\app\AppKernel';
    }
}
