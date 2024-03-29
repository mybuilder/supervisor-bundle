<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests\Command;

use MyBuilder\Bundle\SupervisorBundle\Tests\Fixtures\Command\TestCommand;
use MyBuilder\Bundle\SupervisorBundle\Tests\SupervisorTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class DumpCommandTest extends SupervisorTestCase
{
    private Command $command;

    protected function setUp(): void
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new TestCommand());

        $this->command = $application->find('supervisor:dump');
    }

    protected function tearDown(): void
    {
        self::ensureKernelShutdown();
    }

    public function test_dump_export(): void
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(
            [
                'command' => $this->command->getName(),
                '--user' => 'mybuilder',
                '--server' => 'live',
            ]
        );

        $expected = <<<OUTPUT
[program:supervisor_test-command]
autostart=true
user=mybuilder
name=supervisor_test-command
command=php bin/console supervisor:test-command --env=test
numprocs=1

[program:supervisor_test-command_2]
process_name=%(program_name)s_%(process_num)02d
autostart=true
user=mybuilder
name=supervisor_test-command_2
command=php bin/console supervisor:test-command --env=test
numprocs=2

[program:supervisor_test-command_3]
autostart=true
user=mybuilder
name=supervisor_test-command_3
command=php -d mbstring.func_overload=0 bin/console supervisor:test-command --foo --env=test
numprocs=1
OUTPUT;
        $this->assertEquals($expected, $commandTester->getDisplay());
    }
}
