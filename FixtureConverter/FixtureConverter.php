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
    abstract public function createObject(FixtureData $data);

    /**
     *
     * @param object $object
     * @param array $data
     */
    public function finalizeObject($object, FixtureData $data)
    {

    }

}