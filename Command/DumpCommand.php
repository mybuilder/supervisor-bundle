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

class DumpCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('supervisor:dump')
            ->setDescription('Dump the supervisor configuration for annotated commands')
            ->addOption('user', null, InputOption::VALUE_OPTIONAL, 'The desired user to invoke the command as')
            ->addOption('server', null, InputOption::VALUE_OPTIONAL, 'Only include programs for the specified server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = $this->getApplication()->all();

        /** @var AnnotationSupervisorExporter $exporter */
        $exporter = $this->getContainer()->get('mybuilder.supervisor_bundle.annotation_supervisor_exporter');

        $output->write($exporter->export($commands, $this->parseOptions($input)));
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

    /** @throws \LogicException */
    protected function getContainer(): ContainerInterface
    {
        if (null === $this->container) {
            $application = $this->getApplication();

            if (null === $application) {
                throw new \LogicException('The container cannot be retrieved as the application instance is not yet set.');
            }

            $this->container = $application->getKernel()->getContainer();

            if (null === $this->container) {
                throw new \LogicException('The container cannot be retrieved as the kernel has shut down.');
            }
        }

        return $this->container;
    }
}
