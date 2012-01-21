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
    protected $repositories = array();

    /**
     *
     * @param string $type
     * @param string $key
     * @param mixed $object
     */
    public function set($type, $key, $object)
    {
        $repository = $this->getRepository($type);

        if ($repository->has($key)) {
            throw new RelationManagerException(sprintf('object "%s:%s" exist already', $type, $key));
        }

        $repository->set($key, $object);
    }

    /**
     *
     * @param string $type
     * @param string $key
     * @return mixed
     */
    public function get($type, $key)
    {
        if(!$this->hasRepository($type)) {
            throw new RelationManagerException(sprintf('object "%s:%s" does not exists', $type, $key));
        }

        $repository = $this->getRepository($type);

        if (!$repository->has($key)) {
            throw new RelationManagerException(sprintf('object "%s:%s" does not exists', $type, $key));
        }

        return $repository->get($key);
    }

    /**
     *
     * @param string $type
     * @param string $key
     * @return boolean
     */
    public function has($type, $key)
    {
        return ($this->hasRepository($type) && $this->getRepository($type)->has($key));
    }

    /**
     *
     * @param RepositoryInterface $type
     */
    public function getRepository($type)
    {
        if(!$this->hasRepository($type)) {
            $this->repositories[$type] = new Repository();
        }
        return $this->repositories[$type];
    }

    /**
     *
     * @param string $type
     * @return boolean
     */
    private function hasRepository($type) {
        return isset($this->repositories[$type]);
    }

    /**
     *
     * @return array
     */
    public function getAllObjects()
    {
        $objects = array();
        foreach ($this->repositories as $repository) {
            $objects = array_merge($objects, $repository->getObjects());
        }
        return $objects;
    }

}

