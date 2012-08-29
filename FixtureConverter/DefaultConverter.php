<?php

namespace DavidBadura\FixturesBundle\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureData;
use DavidBadura\FixturesBundle\Exception\FixtureConverterException;
use DavidBadura\FixturesBundle\Util\ObjectAccess\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverter extends FixtureConverter
{

    public function createObject(FixtureData $fixtureData)
    {
        $properties = $fixtureData->getProperties();

        if (!isset($properties['class'])) {
            throw new FixtureConverterException('Missing fixture "class" property');
        }

        $class = $properties['class'];
        $data = $fixtureData->getData();

        $constructor = (isset($properties['constructor'])) ? $properties['constructor'] : array();

        $object = null;
        if (empty($constructor)) {
            $object = new $class();
        } else {
            $args = array();
            foreach ($constructor as $arg) {

                $optional = (substr($arg, 0, 1) == '?');
                $arg = ($optional) ? substr($arg, 1) : $arg;

                if (!isset($data[$arg]) && !$optional) {
                    throw new FixtureConverterException(sprintf('Missing "%s" attribute', $arg));
                } elseif (isset($data[$arg])) {
                    if (is_string($data[$arg])) {
                        $data[$arg] = str_replace('{unique_id}', uniqid(), $data[$arg]);
                    }
                    $args[] = $data[$arg];
                }
            }

            $reflection = new \ReflectionClass($class);
            $object = $reflection->newInstanceArgs($args);
        }

        return $object;
    }

    public function finalizeObject($object, FixtureData $fixtureData)
    {
        $properties = $fixtureData->getProperties();
        $data = $fixtureData->getData();

        $constructor = (isset($properties['constructor'])) ? $properties['constructor'] : array();
        $args = array();

        if (!empty($constructor)) {
            foreach ($constructor as $key) {
                $key = (substr($key, 0, 1) == '?') ? substr($key, 1) : $key;
                $args[$key] = true;
            }
        }

        $objectAccess = new ObjectAccess($object);

        foreach ($data as $property => $value) {
            if (!isset($args[$property])) {
                if (is_string($value)) {
                    $value = str_replace('{unique_id}', uniqid(), $value);
                }
                $objectAccess->writeProperty($property, $value);
            }
        }
    }

    public function getName()
    {
        return 'default';
    }

}
