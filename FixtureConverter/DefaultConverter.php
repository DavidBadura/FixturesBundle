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

        if (!isset($properties['class'])) {
            throw new \Exception('Missing fixture "class" property');
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

        foreach ($data as $property => $value) {
            if (!isset($args[$property])) {
                $this->writeProperty($object, $property, $value);
            }
        }
    }

    public function getName()
    {
        return 'default';
    }

    /**
     * Sets the value of the property at the given index in the path
     *
     * @param object  $objectOrArray The object or array to traverse
     * @param integer $currentIndex  The index of the modified property in the path
     * @param mixed $value           The value to set
     */
    protected function writeProperty($object, $property, $value)
    {
        $reflClass = new \ReflectionClass($object);
        $setter = 'set' . $this->camelize($property);

        if ($reflClass->hasMethod($setter)) {
            if (!$reflClass->getMethod($setter)->isPublic()) {
                throw new FixtureConverterException(sprintf('Method "%s()" is not public in class "%s"', $setter, $reflClass->getName()));
            }

            $object->$setter($value);
        } elseif ($reflClass->hasMethod('__set')) {
            // needed to support magic method __set
            $object->$property = $value;
        } elseif ($reflClass->hasProperty($property)) {
            if (!$reflClass->getProperty($property)->isPublic()) {
                throw new FixtureConverterException(sprintf('Property "%s" is not public in class "%s". Maybe you should create the method "%s()"?', $property, $reflClass->getName(), $setter));
            }

            $object->$property = $value;
        } elseif (property_exists($object, $property)) {
            // needed to support \stdClass instances
            $object->$property = $value;
        } else {
            throw new FixtureConverterException(sprintf('Neither element "%s" nor method "%s()" exists in class "%s"', $property, $setter, $reflClass->getName()));
        }
    }

    protected function camelize($property)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
                    return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
                }, $property);
    }

}