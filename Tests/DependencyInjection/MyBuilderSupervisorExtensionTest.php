<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests\DependencyInjection;

use MyBuilder\Bundle\SupervisorBundle\DependencyInjection\MyBuilderSupervisorExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class MyBuilderSupervisorExtensionTest extends TestCase
{
    /** @var MyBuilderSupervisorExtension */
    private $loader;

    /** @var ContainerBuilder */
    private $container;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->loader = new MyBuilderSupervisorExtension();
    }

    /**
     * @param array $expected
     * @param string $file yaml config file to load
     *
     * @dataProvider providerTestConfig
     */
    public function test_config(array $expected, $file): void
    {
        $this->loader->load($this->getConfig($file), $this->container);

        $this->assertEquals($expected, $this->container->getParameter('mybuilder.supervisor_bundle.exporter_config'));
    }

    public function providerTestConfig(): array
    {
        return [
            [
                [],
                'empty.yml',
            ],
            [
                [
                    'program' => [
                        'autostart' => 'true',
                    ],
                    'executor' => 'php',
                    'console' => 'bin/console',
                ],
                'full.yml',
            ],
        ];
    }

    /**
     * Load the specified yaml config file.
     */
    private function getConfig(string $fileName): array
    {
        $locator = new FileLocator(__DIR__ . '/config');
        $file = $locator->locate($fileName, null, true);

        $config = Yaml::parse(file_get_contents($file));

        if (null === $config) {
            return [];
        }

        return $config;
    }
}
