<?php

namespace DavidBadura\FixturesBundle\RelationManager;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class RelationManager implements RelationManagerInterface, \Countable
{

    protected $repositoryClass = 'DavidBadura\FixturesBundle\RelationManager\Repository';

    /**
     *
     * @var array
     */
    protected $repositories = array();

    /**
     *
     * @param string $type
     */
    public function getRepository($type)
    {
        if (!$this->hasRepository($type)) {
            throw new \Exception(sprintf('repository with the name "%" not exists', $type));
        }
        return $this->repositories[$type];
    }

    /**
     *
     * @param string $type
     * @return boolean
     */
    public function hasRepository($type)
    {
        return isset($this->repositories[$type]);
    }

    /**
     *
     * @param string $type
     * @return RepositoryInterface
     * @throws Exception
     */
    public function createRepository($type)
    {
        if ($this->hasRepository($type)) {
            throw new \Exception(sprintf('repository with the name "%" exists already', $type));
        }
        $repositoryClass = $this->repositoryClass;
        return $this->repositories[$type] = new $repositoryClass();
    }

    /**
     *
     * @return array
     */
    public function getAllObjects()
    {
        $objects = array();
        foreach ($this->repositories as $repository) {
            $objects = array_merge($objects, $repository->toArray());
        }
        return $objects;
    }

    public function count()
    {
        return count($this->repositories);
    }

}

