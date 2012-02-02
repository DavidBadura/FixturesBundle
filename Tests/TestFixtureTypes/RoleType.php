<?php

namespace DavidBadura\FixturesBundle\Tests\TestFixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use DavidBadura\FixturesBundle\Tests\TestObjects\Role;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class RoleType extends FixtureType
{

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

    public function getPersister()
    {
        return 'test-persister';
    }

}