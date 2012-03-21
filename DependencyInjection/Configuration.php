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
                ->scalarNode('persister')->end()
                ->arrayNode('defaults')
                    ->children()
                        ->scalarNode('converter')->end()
                        ->arrayNode('validation')
                            ->children()
                                ->scalarNode('enable')->end()
                                ->scalarNode('group')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}