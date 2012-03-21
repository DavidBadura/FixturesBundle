<?php

namespace DavidBadura\FixturesBundle\FixtureConverter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class FixtureConverter
{

    /**
     *
     * @return string
     */
    abstract public function getName();

    /**
     *
     * @return object
     */
    abstract public function createObject(FixtureData $fixtureData, $properties);

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