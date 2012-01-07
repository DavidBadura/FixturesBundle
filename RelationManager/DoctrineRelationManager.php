<?php

namespace DavidBadura\FixturesBundle\RelationManager;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * 
 * @author David Badura <d.badura@gmx.de>
 */
abstract class DoctrineRelationManager extends RelationManager implements RelationManagerPersister
{

    /**
     *
     * @var ObjectManager
     */
    protected $om;

    /**
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     *
     * @var array
     */
    protected $relations = array();
    
    /**
     *
     * @var array
     */
    protected $newObjects = array();

    /**
     *
     * @var string
     */
    protected $relationObjectClass;
    
    /**
     *
     * @param EntityManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function set($type, $key, $object)
    {
        parent::set($type, $key, $object);
        $this->newObjects[] = array(
            'type' => $type,
            'key' => $key,
            'object' => $object
        );
    }

    public function get($type, $key)
    {
        $this->tryToLoadObject($type, $key);
        return parent::get($type, $key);
    }

    public function has($type, $key)
    {
        try {
            $this->tryToLoadObject($type, $key);
        } catch(RelationManagerException $e) {
            return false;
        }
        return parent::has($type, $key);
    }

    /**
     *
     * @param string $type
     * @param string $key 
     */
    protected function tryToLoadObject($type, $key)
    {
        if (!$this->loaded) {
            $this->load();
        }
        
        if(parent::has($type, $key)) {
            return;
        }

        if (!isset($this->relations[$type]) || !isset($this->relations[$type][$key])) {
            throw new RelationManagerException(sprintf('relation object "%s:%s" konnte nicht gefunden werden', $type, $key));
        }
        
        $relation = $this->relations[$type][$key];

        $object = $this->om->find($relation->getEntityClass(), $relation->getEntityId());
        if (!$object) {
            throw new RelationManagerException(sprintf('entity "%s:%s" konnte nicht gefunden werden', $relation->getEntityClass(), $relation->getEntityId()));
        }

        $this->objects[$type][$key] = $object;
    }
    
    public function save()
    {
        foreach ($this->newObjects as $value) {
            $relation = $this->createRelationObject($value['type'], $value['key'], $value['object']);
            $this->om->persist($relation);
        }
        $this->om->flush();
    }
    
    protected function load() {
        if ($this->loaded)
            return;

        $relations = $this->om->getRepository($this->relationObjectClass)->findAll();

        foreach ($relations as $r) {
            $this->relations[$r->getDataType()][$r->getDataKey()] = $r;
        }

        $this->loaded = true;
    }    

    /**
     * 
     * @param string $type
     * @param string $key
     * @param object $object
     */
    abstract protected function createRelationObject($type, $key, $object);

}

