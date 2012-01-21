<?php

namespace DavidBadura\FixturesBundle\FixtureType;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
abstract class FixtureType
{

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $group;

    /**
     *
     * @var string
     */
    protected $persister;

    /**
     *
     * @var boolean
     */
    protected $validateObjects;

    /**
     *
     * @var string
     */
    protected $validationGroup;

    /**
     *
     * @param array $data
     * @return object
     */
    abstract public function createObject($data);

    /**
     *
     * @param object $object
     * @param array $data
     */
    public function finalizeObject($object, $data)
    {

    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * get the persister name
     *
     * @return string
     */
    public function getPersister()
    {
        return $this->persister;
    }

    /**
     *
     * @return boolean
     */
    public function validateObjects()
    {
        return $this->validateObjects;
    }

    /**
     *
     * @return string
     */
    public function getValidationGroup()
    {
        return $this->validationGroup;
    }

}