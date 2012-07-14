<?php

namespace DavidBadura\FixturesBundle\Util\ObjectAccess;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ObjectAccess
{

    protected $object;
    protected $reflClass;

    public function __construct($object)
    {
        $this->object = $object;
        $this->reflClass = new \ReflectionClass($object);
    }

    public function writeProperty($property, $value)
    {
        $noPublic = array();

        $camelizeProperty = $this->camelize($property);

        $getter = 'get' . $camelizeProperty;
        $setter = 'set' . $camelizeProperty;
        $adder = 'add' . $camelizeProperty;

        /*
         * try with setter method (set*)
         */
        if ($this->reflClass->hasMethod($setter)) {

            if ($this->reflClass->getMethod($setter)->isPublic()) {
                $this->object->$setter($value);

                return;
            }

            $noPublic[] = sprintf('Method "%s()" is not public', $setter);
        }

        /*
         * try with adder method
         */
        if (is_array($value)) {

            if ($this->reflClass->hasMethod($adder)) {

                if ($this->reflClass->getMethod($adder)->isPublic()) {
                    foreach ($value as $val) {
                        $this->object->$adder($val);
                    }

                    return;
                }

                $noPublic[] = sprintf('Method "%s()" is not public', $adder);
            }

            /*
             * try non plural adder
             * remove plural "s"
             */
            if (substr($property, -1, 1) == 's') {
                $singularAdder = 'add' . $this->camelize(substr($property, 0, -1));

                if ($this->reflClass->hasMethod($singularAdder)) {

                    if ($this->reflClass->getMethod($singularAdder)->isPublic()) {
                        foreach ($value as $val) {
                            $this->object->$singularAdder($val);
                        }

                        return;
                    }

                    $noPublic[] = sprintf('Method "%s()" is not public', $singularAdder);
                }
            }

            /*
             * needed to support ArrayCollection
             */
            if ($this->reflClass->hasMethod($getter) && $this->reflClass->getMethod($getter)->isPublic()) {
                $collection = $this->object->$getter();
                if ($collection instanceof \Doctrine\Common\Collections\ArrayCollection) {
                    foreach ($value as $val) {
                        $collection->add($val);
                    }

                    return;
                }
            }
        }

        /*
         * try property
         */
        if ($this->reflClass->hasProperty($property)) {
            if ($this->reflClass->getProperty($property)->isPublic()) {
                $this->object->$property = $value;

                return;
            }

            $noPublic[] = sprintf('Property "%s" is not public. Maybe you should create the method "%s()" or "%s()"?', $property, $setter, $adder);
        }

        /*
         * needed to support \stdClass instances
         */
        if ($this->object instanceof \stdClass) {
            $this->object->$property = $value;

            return;
        }

        /*
         * try with magic __set method
         */
        if ($this->reflClass->hasMethod('__set')) {
            $this->object->$property = $value;

            return;
        }

        if (count($noPublic) > 0) {
            throw new ObjectAccessException(sprintf('property "%s" is not writeable in class "%s"' . "\n"
                . implode("\n", $noPublic), $property, $this->reflClass->getName()));
        }

        throw new ObjectAccessException(sprintf('property "%s" is not writeable in class "%s"' . "\n"
            . 'Maybe you should create the method "%s()" or "%s()"?', $property, $this->reflClass->getName(), $setter, $adder));
    }

    protected function camelize($property)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
                    return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
                }, $property);
    }

}
