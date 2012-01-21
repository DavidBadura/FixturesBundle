<?php

namespace DavidBadura\FixturesBundle\Tests\TestFixtureTypes;

use DavidBadura\FixturesBundle\FixtureType\FixtureType;
use DavidBadura\FixturesBundle\Configuration as Fixture;

/**
 * @author David Badura <d.badura@gmx.de>
 *
 * @Fixture\Type(name="annotations", group="install")
 * @Fixture\Validation(group="test")
 * @Fixture\Persister(name="orm")
 */
class AnnotationsType extends FixtureType
{

    public function createObject($data)
    {
        return new \stdClass();
    }

}