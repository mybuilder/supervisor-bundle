<?php

namespace MyBuilder\Bundle\SupervisorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('my_builder_supervisor');

        $rootNode
            ->children()
                ->arrayNode('exporter')
                    ->children()
                        ->variableNode('program')->end()
                        ->scalarNode('executor')->example('php')->end()
                        ->scalarNode('console')->example('app/console (bin/console)')->end()
                    ->end()
            ->end();

        return $treeBuilder;
    }
}
