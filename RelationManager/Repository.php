<?php

namespace DavidBadura\FixturesBundle\RelationManager;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Repository implements RepositoryInterface
{

    /**
     *
     * @var array
     */
    protected $objects = array();

    /**
     *
     * @param string $key
     * @return object
     * @throws \Exception
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \Exception(sprintf('object with the key "%s" not exist', $key));
        }
        return $this->objects[$key];
    }

    /**
     *
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->objects[$key]);
    }

    /**
     *
     * @param string $key
     * @param object $object
     * @throws \Exception
     */
    public function set($key, $object)
    {
        if ($this->has($key)) {
            throw new \Exception(sprintf('object with the key "%s" exist already', $key));
        }
        $this->objects[$key] = $object;
    }

    /**
     *
     * @return integer
     */
    public function count()
    {
        return count($this->objects);
    }

    /**
     *
     * @return array
     */
    public function toArray() {
        return $this->objects;
    }

}