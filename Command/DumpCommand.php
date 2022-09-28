<?php

namespace MyBuilder\Bundle\SupervisorBundle\Command;

use MyBuilder\Bundle\SupervisorBundle\Exporter\AnnotationSupervisorExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DumpCommand extends Command
{
    private AnnotationSupervisorExporter $annotationSupervisorExporter;

    public function __construct(AnnotationSupervisorExporter $annotationSupervisorExporter)
    {
        $this->annotationSupervisorExporter = $annotationSupervisorExporter;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('supervisor:dump')
            ->setDescription('Dump the supervisor configuration for annotated commands')
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, 'The desired user to invoke the command as')
            ->addOption('server', null, InputOption::VALUE_OPTIONAL, 'Only include programs for the specified server');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = $this->getApplication()->all();

        $output->write($this->annotationSupervisorExporter->export($commands, $this->parseOptions($input)));

        return 0;
    }

    private function parseOptions(InputInterface $input): array
    {
        $options = [];

        if ($user = $input->getOption('user')) {
            $options['user'] = $user;
        }

        if ($env = $input->getOption('env')) {
            $options['environment'] = $env;
        }

        if ($server = $input->getOption('server')) {
            $options['server'] = $server;
        }

        return $options;
    }
}
