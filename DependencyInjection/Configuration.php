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
        $rootNode = $treeBuilder->root('david_badura_fixtures', 'array');

        $rootNode
            ->children()
                ->arrayNode('bundles')
                    ->prototype('scalar')->isRequired()->end()
                ->end()
                ->scalarNode('persister')->defaultValue('orm')->end()
                ->arrayNode('defaults')
                    ->children()
                        ->scalarNode('converter')->defaultValue('default')->end()
                        ->arrayNode('validation')
                            ->children()
                                ->scalarNode('enable')->defaultValue(true)->end()
                                ->scalarNode('group')->defaultValue('Default')->end()
                            ->end()
                        ->end()
                        ->arrayNode('parameters')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}