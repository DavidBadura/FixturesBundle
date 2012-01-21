<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\RelationManager\RelationManagerInterface;
use DavidBadura\FixturesBundle\Persister\PersisterInterface;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureLoader
{

    /**
     *
     * @var RelationManagerInterface
     */
    protected $rm;

    /**
     *
     * @var array
     */
    private $persisters = array();

    /**
     *
     * @var array
     */
    private $stack;


    /**
     *
     * @param Executor $executor
     */
    public function __construct(RelationManager $rm)
    {
        $this->rm = $rm;
    }

    /**
     *
     * @param PersisterInterface $persister
     */
    public function addPersister($name, PersisterInterface $persister)
    {
        if (isset($this->persisters[$name])) {
            throw new \Exception(sprintf('a persister with the name "%s" exist already', $name));
        }
        $this->persisters[$name] = $persister;
        return $this;
    }

    /**
     *
     * @param array $data
     * @param array $types
     * @param array $options
     */
    public function loadFixtures(array $data, array $types, array $options = array())
    {

        $defaultOptions = array(
            'no-validate' => false,
            'no-persist' => false,
            'group' => null
        );

        $options = array_merge($defaultOptions, $options);


        $rm = $this->rm;

        if (isset($this->stack[$type][$key])) {

        }

        $this->stack[$type][$key] = true;

        array_walk_recursive($data[$type][$key], function(&$value, $key) use ($rm, $data) {
                if (preg_match('/^@(\w*):(\w*)$/', $value, $hit)) {
                    if (!$rm->has($hit[1], $hit[2])) {
                        $value = $this->createObject($hit[1], $hit[2], $data);
                    } else {
                        $value = $rm->get($hit[1], $hit[2]);
                    }
                }
            });

        $this->types[$type]->setRelationManager($this->rm);
        $object = $this->types[$type]->createObject($data[$type][$key]);
        $rm->set($type, $key, $object);

        unset($this->stack[$type][$key]);

        return $object;

            $objects = $this->createObjects($data);

        return $objects;
    }

    /**
     *
     * @param array $data
     * @return type
     */
    private function createObjects(array $data)
    {
        return $this->executor->execute($this->types, $data);
    }


    /**
     *
     * @param array $objects
     * @return type
     */
    private function persistObjects(array $objects)
    {

        foreach ($this->types as $type) {

        }
    }

}