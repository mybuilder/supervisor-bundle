<?php

namespace MyBuilder\Bundle\SupervisorBundle\Exporter;

use Doctrine\Common\Annotations\Reader;
use Francodacosta\Supervisord\Configuration;
use Francodacosta\Supervisord\Processors\CommandConfigurationProcessor;
use MyBuilder\Bundle\SupervisorBundle\Annotation\Supervisor as SupervisorAnnotation;
use Symfony\Component\Console\Command\Command;

class AnnotationSupervisorExporter
{
    private Reader $reader;
    private array $config;

    public function __construct(Reader $reader, array $config)
    {
        $this->reader = $reader;
        $this->config = $config;
    }

    /**
     * @param Command[] $commands
     * @param array $options Runtime options that have been defined
     *
     * @return string The supervisor configuration
     */
    public function export(array $commands, array $options): string
    {
        $programs = $this->toParsedPrograms($commands, $options);

        if (empty($programs)) {
            return '';
        }

        $configuration = $this->buildConfiguration($programs);

        return $configuration->generate();
    }

    private function toParsedPrograms(array $commands, array $options)
    {
        $config = isset($this->config['program']) ? (array) $this->config['program'] : [];
        [$env, $server, $options] = $this->takeEnvironmentAndServerFrom($options);
        $config += $options;

        return array_reduce(
            $commands,
            function (array $programs, Command $command) use ($env, $server, $config) {
                foreach ($this->toSupervisorAnnotations($command, $server) as $instance => $annotation) {
                    $programs[] = $config + [
                            'name' => $this->buildProgramName($command->getName(), $instance),
                            'command' => $this->buildCommand($command->getName(), $env, $annotation),
                            'numprocs' => $annotation->processes,
                        ];
                }

                return $programs;
            }, []
        );
    }

    private function takeEnvironmentAndServerFrom(array $options): array
    {
        $env = $options['environment'] ?? null;
        $server = $options['server'] ?? null;

        unset($options['environment'], $options['server']);

        return [$env, $server, $options];
    }

    private function toSupervisorAnnotations(Command $command, $server): array
    {
        $annotations = $this->reader->getClassAnnotations(new \ReflectionClass($command));

        $filtered = array_filter(
            $annotations,
            static function ($annotation) use ($server) {
                if ($annotation instanceof SupervisorAnnotation) {
                    return null === $server || $server === $annotation->server;
                }

                return false;
            }
        );

        if (empty($filtered)) {
            return [];
        }

        return array_combine(range(1, count($filtered)), array_values($filtered));
    }

    private function buildProgramName(string $commandName, int $instance): string
    {
        $name = str_replace(':', '_', $commandName);

        return (1 === $instance) ? $name : "{$name}_{$instance}";
    }

    private function buildCommand(string $commandName, string $environment, SupervisorAnnotation $annotation): string
    {
        $executor = '';

        if ($annotation->executor) {
            $executor = $annotation->executor;
        } elseif (isset($this->config['executor'])) {
            $executor = $this->config['executor'];
        }

        $console = isset($this->config['console']) ? " {$this->config['console']}" : '';
        $params = $annotation->params ? " {$annotation->params}" : '';
        $env = $environment ? " --env=$environment" : '';

        return $executor . $console . ' ' . $commandName . $params . $env;
    }

    private function buildConfiguration(array $programs): Configuration
    {
        $config = new Configuration;
        $config->registerProcessor(new CommandConfigurationProcessor);

        return array_reduce(
            $programs,
            static function (Configuration $config, $program) {
                $command = new \Francodacosta\Supervisord\Command;

                foreach ($program as $k => $v) {
                    $command->set($k, $v);
                }

                $config->add($command);

                return $config;
            }, $config
        );
    }
}
