<?php

namespace DavidBadura\FixturesBundle;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureData
{

    /**
     *
     * @var string
     */
    protected $key;

    /**
     *
     * @var array
     */
    protected $data;

    /**
     *
     * @var object
     */
    protected $object;

    /**
     *
     * @var Fixture
     */
    protected $fixture;

    /**
     *
     * @param string $key
     * @param array $data
     */
    public function __construct($key, array $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    /**
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * @param array $data
     * @return \FixtureData
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     *
     * @param object $object
     * @return \DavidBadura\FixturesBundle\FixtureData
     */
    public function setObject($object)
    {
        if($this->object) {
            throw new \Exception();
        }
        $this->object = $object;
        return $this;
    }

    /**
     *
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->fixture->getProperties();
    }

    /**
     *
     * @param Fixture $fixture
     * @throws \Exception
     */
    public function setFixture(Fixture $fixture)
    {
        if($this->fixture) {
            throw new \Exception();
        }
        $this->fixture = $fixture;
    }

    /**
     *
     * @return Fixture
     */
    public function getFixture()
    {
        return $this->fixture;
    }

}