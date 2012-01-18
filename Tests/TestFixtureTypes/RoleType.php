<?php

namespace DavidBadura\FixturesBundle\Tests\TestFixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class RoleType extends FixtureType
{

    public function addNodeSchema(NodeBuilder $node)
    {
        $node->scalarNode('name')->isRequired()->end();
    }

    public function createObject($data)
    {
        $role = new Role();
        $role->name = $data['name'];
        return $role;
    }

    public function getName()
    {
        return 'role';
    }

}

class Role
{

    /**
     *
     * @var string
     */
    public $name;

}