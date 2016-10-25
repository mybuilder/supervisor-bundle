<?php

namespace MyBuilder\Bundle\SupervisorBundle\Tests\DependencyInjection;

use MyBuilder\Bundle\SupervisorBundle\DependencyInjection\MyBuilderSupervisorExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class MyBuilderSupervisorExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MyBuilderSupervisorExtension
     */
    private $loader;

    /**
     * @var ContainerBuilder
     */
    private $container;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->loader = new MyBuilderSupervisorExtension();
    }

    /**
     * @param array  $expected
     * @param string $file yaml config file to load
     *
     * @dataProvider providerTestConfig
     */
    public function testConfig(array $expected, $file)
    {
        $this->loader->load($this->getConfig($file), $this->container);

        $this->assertEquals($expected, $this->container->getParameter('mybuilder.supervisor_bundle.exporter_config'));
    }

    public function providerTestConfig()
    {
        return [
            [
                [],
                'empty.yml'
            ],
            [
                [
                    'program' => [
                        'autostart' => 'true'
                    ],
                    'executor' => 'php',
                    'console' => 'app/console',
                ],
                'full.yml'
            ],
        ];
    }

    /**
     * Load the specified yaml config file.
     *
     * @param string $fileName
     *
     * @return array
     */
    private function getConfig($fileName)
    {
        $locator = new FileLocator(__DIR__ . '/config');
        $file = $locator->locate($fileName, null, true);

        $config = Yaml::parse(file_get_contents($file));
        if (null === $config) {
            return array();
        }

        return $config;
    }
}