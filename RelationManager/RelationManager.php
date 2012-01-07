<?php

namespace DavidBadura\FixturesBundle\RelationManager;

/**
 * 
 * @author David Badura <d.badura@gmx.de>
 */
class RelationManager implements RelationManagerInterface
{

    /**
     *
     * @var array
     */
    protected $objects = array();

    /**
     *
     * @param string $type
     * @param string $key
     * @param mixed $object 
     */
    public function set($type, $key, $object)
    {

        if ($this->has($type, $key)) {
            throw new RelationManagerException(sprintf('object "%s:%s" exist already', $type, $key));
        }

        $this->objects[$type][$key] = $object;
    }

    /**
     *
     * @param string $type
     * @param string $key
     * @return mixed
     */
    public function get($type, $key)
    {

        if (!$this->has($type, $key)) {
            throw new RelationManagerException(sprintf('object "%s:%s" does not exists', $type, $key));
        }

        return $this->objects[$type][$key];
    }

    /**
     *
     * @param string $type
     * @param string $key
     * @return boolean
     */
    public function has($type, $key)
    {
        return (isset($this->objects[$type]) 
            && is_array($this->objects[$type]) 
                && isset($this->objects[$type][$key]));
    }

    /**
     * @return array
     */
    public function getObjects()
    {
        return $this->objects;
    }

}

