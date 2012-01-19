<?php

namespace DavidBadura\FixturesBundle\Executor;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\NodeInterface;
use DavidBadura\FixturesBundle\Persister\PersisterInterface;
use DavidBadura\FixturesBundle\RelationManager\RelationManagerInterface;
use DavidBadura\FixturesBundle\RelationManager\RelationManagerPersister;
use DavidBadura\FixturesBundle\FixtureType\FixtureType;

/**
 * 
 * @author David Badura <d.badura@gmx.de>
 */
class Executor
{

    /**
     *
     * @var RelationManagerInterface
     */
    protected $rm;
    
    /**
     *
     * @var PersisterInterface
     */
    protected $persister;
    
    /**
     *
     * @var array
     */
    protected $types = array();
    
    /**
     *
     * @var type 
     */
    protected $logger;
    
    /**
     * @var string
     */
    protected $root = 'fixtures';

    /**
     *
     * @param RelationManagerInterface $rm 
     * @param PersisterInterface $persister
     */
    public function __construct(RelationManagerInterface $rm, PersisterInterface $persister)
    {
        $this->rm = $rm;
        $this->persister = $persister;
    }
    
    /**
     * @return RelationManagerInterface
     */
    public function getRelationManager()
    {
        return $this->rm;
    }
    
    /**
     * @return RelationManagerInterface
     */
    public function getObjectPersister()
    {
        return $this->persister;
    }    

    /**
     *
     * @param FixtureType $type
     * @return Executor 
     */
    public function addFixtureType(FixtureType $fixtureType)
    {

        if (isset($this->types[$fixtureType->getName()])) {
            throw new ExecutorException(sprintf('the fixture type %s exist already', $fixtureType->getName()));
        }

        $this->types[$fixtureType->getName()] = $fixtureType;

        return $this;
    }

    /**
     *
     * @param type $data 
     */
    public function execute($data, $test = false)
    {
        $tree = $this->createTree();
        $data = $this->validate($tree, $data);
        $objects = $this->createObjects($data);
        
        if(!$test) {
            $this->persister->save($objects);
            if($this->rm instanceof RelationManagerPersister) {
                $this->rm->save();
            }
        }
        return $objects;
    }

    /**
     *
     * @param string $root
     * @return NodeInterface 
     */
    protected function createTree()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($this->root);
        $node->disallowNewKeysInSubsequentConfigs();

        $node = $node->children();

        foreach ($this->types as $type) {

            $node = $node->arrayNode($type->getName())->useAttributeAsKey('key')->prototype('array')->children();
            $type->addNodeSchema($node);
            $node = $node->end()->end()->end();
        }

        $node = $node->end();

        return $treeBuilder->buildTree();
    }
    
    /**
     *
     * @param NodeInterface $tree
     * @param array $data
     * @return array 
     */
    protected function validate(NodeInterface $tree, $data) {
        $data = $tree->normalize($data);
        $data = $tree->finalize($data);
        return $data;
    }

    /**
     *
     * @param array $data 
     */
    protected function createObjects($data)
    {
        
        $objects = array();

        foreach ($this->types as $type) {

            $this->log($type->getName());
            $type->setRelationManager($this->rm);

            foreach ($data[$type->getName()] as $key => $values) {
                try {
                    $values = $this->prepareData($values);
                    $objects[] = $object = $type->createObject($values);
                    $this->rm->set($type->getName(), $key, $object);
                } catch (\Exception $e) {
                    throw new ExecutorException(sprintf("Error by %s:%s", $type->getName(), $key), null, $e);
                }
            }
        }
        
        return $objects;
    }
    
    /**
     *
     * @param \Closure $logger
     * @return self 
     */
    public function setLogger(\Closure $logger) {
        $this->logger = $logger;
        return $this;
    }
    
    /**
     *
     * @param string $message 
     */
    protected function log($message) {
        $logger = $this->logger;
        if($logger) {
            $logger($message);
        }
    }
    
    protected function prepareData($data) {
        
     $rm = $this->rm;
     array_walk_recursive($data, function(&$value, &$key) use ($rm) {
             if(preg_match('/^@(\w*):(\w*)$/', $value, $hit)) {
                 $value = $rm->get($hit[1], $hit[2]);
             }
             
             if(preg_match('/^@(\w*):(\w*)$/', $key, $hit)) {
                 $key = $rm->get($hit[1], $hit[2]);
             }             
     });
        
     return $data;
    }

}

