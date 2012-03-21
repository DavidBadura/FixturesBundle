<?php

namespace DavidBadura\FixturesBundle\FixtureConverter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverter extends FixtureConverter
{

    public function createObject(FixtureData $data)
    {
        return new stdClass($data->getData());
    }

    public function getName()
    {
        return 'default';
    }

}