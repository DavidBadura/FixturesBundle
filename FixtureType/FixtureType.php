<?php

namespace DavidBadura\FixturesBundle\FixtureType;


use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use DavidBadura\FixturesBundle\RelationManager\RelationManagerInterface;

/**
 * 
 * @author David Badura <d.badura@gmx.de>
 */
abstract class FixtureType {
    
    /**
     *
     * @var RelationManager
     */
    private $rm;
    
    /**
     *
     * @param RelationManagerInterface $rm
     */
    public function setRelationManager(RelationManagerInterface $rm) {
        $this->rm = $rm;
    }
    
    /**
     *
     * @param string $type
     * @param string $key
     * @return mixed 
     */
    public function get($type, $key) {
        return $this->rm->get($type, $key);
    }
    
    /**
     *
     * @param string $type
     * @param string $key
     * @return mixed 
     */
    public function has($type, $key) {
        return $this->rm->has($type, $key);
    }
    
    /**
     * @return integer
     */
    public function getOrder() {
        return 0;
    }

    /**
     * @param NodeBuilder $node
     */
    abstract function addNodeSchema(NodeBuilder $node);
    
    /**
     * @param array $data
     */
    abstract function createObject($data);
    
    /**
     * @return string
     */
    abstract function getName();
    
}