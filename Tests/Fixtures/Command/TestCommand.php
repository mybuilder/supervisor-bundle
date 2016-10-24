<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests\Fixtures\Command;

use Symfony\Component\Console\Command\Command;
use MyBuilder\Bundle\SupervisorBundle\Annotation\Supervisor;

/**
 * Empty command for testing that this bundle can read the supervisor annotations correctly
 *
 * @Supervisor(processes=1)
 * @Supervisor(processes=2)
 * @Supervisor(processes=1, executor="php -d mbstring.func_overload=0", params="--foo")
 */
class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('supervisor:test-command');
    }
}
