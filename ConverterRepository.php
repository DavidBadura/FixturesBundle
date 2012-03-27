<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverterInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ConverterRepository
{

    /**
     *
     * @var FixtureConverterInterface[]
     */
    private $converters = array();

    /**
     *
     * @param FixtureConverterInterface $converter
     * @return \DavidBadura\FixturesBundle\FixtureManager
     * @throws \Exception
     */
    public function addConverter(FixtureConverterInterface $converter)
    {
        $name = $converter->getName();
        if (isset($this->converters[$name])) {
            throw new \Exception(sprintf('Converter "%s" exist already', $name));
        }

        $this->converters[$name] = $converter;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function hasConverter($name)
    {
        return isset($this->converters[$name]);
    }

    /**
     *
     * @param string $name
     * @return FixtureConverterInterface
     * @throws \Exception
     */
    public function getConverter($name)
    {
        if (!$this->hasConverter($name)) {
            throw new \Exception(sprintf('Converter "%s" not exist', $name));
        }

        return $this->converters[$name];
    }

    /**
     *
     * @param string $name
     * @return \DavidBadura\FixturesBundle\FixtureManager
     * @throws \Exception
     */
    public function removeConverter($name)
    {
        if (!$this->hasConverter($name)) {
            throw new \Exception(sprintf('Converter "%s" not exist', $name));
        }

        unset($this->converters[$name]);
        return $this;
    }

}

