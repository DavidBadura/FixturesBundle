<?php

namespace DavidBadura\FixturesBundle\Tests\TestFixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use DavidBadura\FixturesBundle\Tests\TestObjects\User;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class UserType extends FixtureType
{

    public function createObject($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->roles = $data['roles'];
        $user->groups = $data['groups'];
        return $user;
    }

    public function getName()
    {
        return 'user';
    }

}