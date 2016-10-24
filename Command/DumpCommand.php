<?php

namespace MyBuilder\Bundle\SupervisorBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('supervisor:dump')
            ->setDescription('Dump the supervisor configuration for annotated commands')
            ->addOption('user', '', InputOption::VALUE_OPTIONAL, 'The desired user to invoke the command as');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = $this->getApplication()->all();

        $exporter = $this->getContainer()->get('mybuilder.supervisor_bundle.annotation_supervisor_exporter');

        $output->write($exporter->export($commands, $this->parseOptions($input)));
    }

    private function parseOptions(InputInterface $input)
    {
        $options = [];

        if ($user = $input->getOption('user')) {
            $options['user'] = $user;
        }

        if ($env = $input->getOption('env')) {
            $options['environment'] = $env;
        }

        return $options;
    }
}
