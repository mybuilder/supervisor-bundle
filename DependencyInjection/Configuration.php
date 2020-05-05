<?php

namespace MyBuilder\Bundle\SupervisorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            // Symfony 4
            $treeBuilder = new TreeBuilder('my_builder_supervisor');
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // Symfony 3
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('my_builder_supervisor');
        }

        $rootNode
            ->children()
                ->arrayNode('exporter')
                    ->children()
                        ->variableNode('program')->end()
                        ->scalarNode('executor')->example('php')->end()
                        ->scalarNode('console')->example('bin/console')->end()
                    ->end()
            ->end();

        return $treeBuilder;
    }
}
