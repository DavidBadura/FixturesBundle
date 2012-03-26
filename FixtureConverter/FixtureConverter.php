<?php

namespace DavidBadura\FixturesBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class FixtureConverter implements FixtureConverterInterface
{

    /**
     *
     * @param object $object
     * @param array $data
     */
    public function finalizeObject($object, FixtureData $fixtureData)
    {

    }

    /**
     *
     * @param array $properties
     * @return array
     */
    public function prepareProperties(array $properties)
    {
        return $properties;
    }

}