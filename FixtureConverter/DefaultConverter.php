<?php

namespace DavidBadura\FixturesBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureData;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverter extends FixtureConverter
{

    public function createObject(FixtureData $fixtureData)
    {
        $properties = $fixtureData->getProperties();

        $data = $fixtureData->getData();
        $class = $properties['class'];

        $object = new $class();
        foreach ($data as $key => $value) {
            $this->setVar($object, $key, $value);
        }

        return $object;
    }

    public function getName()
    {
        return 'default';
    }

    public function prepareProperties(array $properties)
    {
        if (!isset($properties['class'])) {
            throw new \Exception();
        }

        return $properties;
    }

    private function setVar($object, $key, $value)
    {
        $object->$key = $value;
    }

}