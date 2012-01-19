<?php

namespace DavidBadura\FixturesBundle\Tests\TestFixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use DavidBadura\FixturesBundle\Tests\TestObjects\Group;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class GroupType extends FixtureType
{

    public function addNodeSchema(NodeBuilder $node)
    {
        $node->scalarNode('name')->isRequired()->end()
            ->scalarNode('leader')->isRequired()->end()
        ;
    }

    public function createObject($data)
    {
        $group = new Group();
        $group->name = $data['name'];
        $group->leader = $data['leader'];
        return $group;
    }

    public function getName()
    {
        return 'group';
    }

}