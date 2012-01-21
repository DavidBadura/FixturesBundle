<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\RelationManager\RelationManagerInterface;
use DavidBadura\FixturesBundle\Persister\PersisterInterface;
use DavidBadura\FixturesBundle\FixtureType\FixtureType;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureLoader
{

    /**
     *
     * @var RelationManagerInterface
     */
    private $rm;

    /**
     *
     * @var array
     */
    private $persisters = array();

    /**
     *
     * @var array
     */
    private $loaded = array();

    /**
     *
     * @var array
     */
    private $stack = array();

    /**
     *
     * @var \Closure
     */
    private $logger;

    /**
     *
     * @param Executor $executor
     */
    public function __construct(RelationManagerInterface $rm)
    {
        $this->rm = $rm;
    }

    /**
     *
     * @return RelationManagerInterface
     */
    public function getRelationManager()
    {
        return $this->rm;
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
     * @param \Closure $logger
     */
    public function setLogger(\Closure $logger)
    {
        $this->logger = $logger;
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
            'no-persist' => false
        );

        $options = array_merge($defaultOptions, $options);

        $types = $this->prepareTypes($types);

        $this->createObjects($data, $types);
        $this->finalizeObjects($data, $types);

        if (!$options['no-validate']) {
            $this->validateObjects();
        }

        if (!$options['no-persist']) {
            $this->persistObjects();
        }
    }

    /**
     *
     * @param array $data
     * @param array $types
     */
    private function createObjects(array &$data, array &$types)
    {
        $this->stack = array();

        foreach ($data as $objectType => $keys) {
            foreach (array_keys($keys) as $objectKey) {

                if (!isset($types[$objectType])) {
                    continue;
                }

                if (isset($this->loaded[$objectType][$objectKey])) {
                    continue;
                }

                $this->createObject($objectType, $objectKey, $data, $types);
            }
        }
    }

    /**
     *
     * @param array $data
     * @param array $types
     */
    private function finalizeObjects(array &$data, array &$types)
    {

        foreach ($data as $objectType => $keys) {
            foreach (array_keys($keys) as $objectKey) {

                if (!isset($types[$objectType])) {
                    continue;
                }

                $this->finalizeObject($objectType, $objectKey, $data, $types);
            }
        }
    }

    /**
     *
     * @param array $data
     * @param FixtureType $fixtureType
     * @param string $objectType
     * @param string $objectKey
     * @throws \Exception
     */
    private function createObject($objectType, $objectKey, array &$data, &$types)
    {

        if (!isset($types[$objectType])) {
            throw new \Exception(sprintf('type "%s" not exist', $objectType));
        }

        if (!isset($data[$objectType][$objectKey])) {
            throw new \Exception(sprintf('data for "%s:%s" not exist', $objectType, $objectKey));
        }

        if (isset($this->stack[$objectType . ':' . $objectKey])) {
            throw new \Exception('circle');
        }

        $this->stack[$objectType . ':' . $objectKey] = true;
        $loader = $this;

        array_walk_recursive($data[$objectType][$objectKey], function(&$value, $key) use ($loader, &$data, &$types) {
                if (preg_match('/^@(\w*):(\w*)$/', $value, $hit)) {

                    if (!$loader->rm->hasRepository($hit[1])
                        || !$loader->rm->getRepository($hit[1])->has($hit[2])) {

                        $loader->createObject($hit[1], $hit[2], $data, $types);
                    }

                    $value = $loader->rm->getRepository($hit[1])->get($hit[2]);
                }
            });

        $object = $types[$objectType]->createObject($data[$objectType][$objectKey]);

        if (!$this->rm->hasRepository($objectType)) {
            $this->rm->createRepository($objectType);
        }

        $this->rm->getRepository($objectType)->set($objectKey, $object);
        unset($this->stack[$objectType . ':' . $objectKey]);
    }

    /**
     *
     * @param array $data
     * @param FixtureType $fixtureType
     * @param string $objectType
     * @param string $objectKey
     * @throws \Exception
     */
    private function finalizeObject($objectType, $objectKey, array &$data, &$types)
    {

        $loader = $this;
        array_walk_recursive($data[$objectType][$objectKey], function(&$value, $key) use ($loader, &$data, &$types) {

                if (!is_string($value)) {
                    return;
                }

                if (preg_match('/^@@(\w*):(\w*)$/', $value, $hit)) {

                    if (!$loader->rm->hasRepository($hit[1])
                        || !$loader->rm->getRepository($hit[1])->has($hit[2])) {

                        throw new \Exception();
                    }

                    $value = $loader->rm->getRepository($hit[1])->get($hit[2]);
                }
            });

        $object = $this->rm->getRepository($objectType)->get($objectKey);
        $types[$objectType]->finalizeObject($object, $data[$objectType][$objectKey]);
    }

    /**
     *
     */
    private function validateObjects()
    {

    }

    /**
     *
     */
    private function persistObjects()
    {

    }

    /**
     *
     * @param array $types
     * @return array
     */
    private function prepareTypes(array $types)
    {
        $tempTypes = array();
        foreach ($types as $type) {
            if (!$type instanceof FixtureType) {
                throw new \Exception();
            }
            $tempTypes[$type->getName()] = $type;
        }
        return $tempTypes;
    }

    /**
     *
     * @param string $message
     */
    private function log($message)
    {
        if ($this->logger) {
            $this->logger($message);
        }
    }

}