<?php

namespace DavidBadura\FixturesBundle\RelationManager;

/**
 * 
 * @author David Badura <d.badura@gmx.de>
 */
class ORMRelationManager extends DoctrineRelationManager
{

    protected $proxyClass = 'Doctrine\ORM\Proxy\Proxy';
    
    protected $relationObjectClass = 'DavidBadura\FixturesBundle\Entity\DataRelation';
  
    protected function createRelationObject($type, $key, $object)
    {
        if($object instanceof $this->proxyClass) {
            $class = get_parent_class($object);
        } else {
            $class = get_class($object);
        }

        // TODO
        $id = $object->getId();

        $relationClass = $this->relationObjectClass;
        return new $relationClass($type, $key, $class, $id);
    }
    
    
}

