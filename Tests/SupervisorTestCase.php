<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests;

use MyBuilder\Bundle\SupervisorBundle\Tests\Fixtures\app\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SupervisorTestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        return AppKernel::class;
    }
}
