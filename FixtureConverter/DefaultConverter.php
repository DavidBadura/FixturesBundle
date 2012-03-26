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



        $constructor = (isset($properties['constructor'])) ? $properties['constructor'] : array() ;

        $object = null;
        if(empty($constructor)) {
            $object = new $class();
        } else {
            $args = array();
            foreach($constructor as $arg) {
                $args[] = $data[$arg];
            }

            $reflection = new \ReflectionClass($class);
            $object = $reflection->newInstanceArgs($args);
        }

        foreach ($data as $property => $value) {
            $this->writeProperty($object, $property, $value);
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
        $setter = 'set'.$this->camelize($property);

        if ($reflClass->hasMethod($setter)) {
            if (!$reflClass->getMethod($setter)->isPublic()) {
                throw new PropertyAccessDeniedException(sprintf('Method "%s()" is not public in class "%s"', $setter, $reflClass->getName()));
            }

            $object->$setter($value);
        } elseif ($reflClass->hasMethod('__set')) {
            // needed to support magic method __set
            $objectOrArray->$property = $value;
        } elseif ($reflClass->hasProperty($property)) {
            if (!$reflClass->getProperty($property)->isPublic()) {
                throw new PropertyAccessDeniedException(sprintf('Property "%s" is not public in class "%s". Maybe you should create the method "%s()"?', $property, $reflClass->getName(), $setter));
            }

            $objectOrArray->$property = $value;
        } elseif (property_exists($object, $property)) {
            // needed to support \stdClass instances
            $objectOrArray->$property = $value;
        } else {
            throw new InvalidPropertyException(sprintf('Neither element "%s" nor method "%s()" exists in class "%s"', $property, $setter, $reflClass->getName()));
        }
    }

    protected function camelize($property)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) { return ('.' === $match[1] ? '_' : '').strtoupper($match[2]); }, $property);
    }

}