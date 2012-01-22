<?php

namespace DavidBadura\FixturesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * FixtureBundle configuration structure.
 *
 * @author David Badura <d.badura@gmx.de>
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return Symfony\Component\Config\Definition\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('davidbadura_fixtures', 'array');

        $rootNode
            ->children()
                ->scalarNode('annotation')->defaultValue(true)->end()
                ->arrayNode('bundles')
                    ->prototype('scalar')->isRequired()->end()
                ->end()
                ->arrayNode('persisters')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('name')->isRequired()->end()
                        ->scalarNode('type')->isRequired()->end()
                        ->scalarNode('service')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}