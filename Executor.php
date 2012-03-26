<?php

namespace DavidBadura\FixturesBundle;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class Executor
{

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
     * @var type
     */
    private $order = 0;


    /**
     *
     * @param Fixture[] $data
     */
    public function execute(array $fixtures)
    {
        $this->checkFixtures($fixtures);
        $this->createObjects($fixtures);
        return $this->finalizeObjects($fixtures);
    }

    /**
     *
     * @param array $data
     * @param array $types
     */
    private function createObjects(array $fixtures)
    {
        $this->stack = array();

        foreach ($fixtures as $fixture) {
            foreach ($fixture as $data) {

                if (isset($this->loaded[$fixture->getName()][$data->getKey()])) {
                    continue;
                }

                $this->createObject($fixtures, $fixture->getName(), $data->getKey());
            }
        }
    }

    /**
     *
     * @param array $data
     * @param array $types
     */
    private function finalizeObjects(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            foreach ($fixture as $data) {
                $this->finalizeObject($fixtures, $fixture->getName(), $data->getKey());
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
    public function createObject($fixtures, $name, $key)
    {

        if (isset($this->stack[$name . ':' . $key])) {
            throw new \Exception('circle');
        }

        $this->stack[$name . ':' . $key] = true;

        $fixture = $fixtures[$name];
        $fixtureData = $fixture->getFixtureData($key);

        $executor = $this;
        $data = $fixtureData->getData();
        array_walk_recursive($data, function(&$value, $key) use ($executor, $fixtures) {
                if (preg_match('/^@(\w*):(\w*)$/', $value, $hit)) {

                    if(!isset($fixtures[$hit[1]]) || !$fixtures[$hit[1]]->getFixtureData($hit[2])) {
                        throw new \Exception();
                    }

                    $object = $fixtures[$hit[1]]->getFixtureData($hit[2])->getObject();

                    if(!$object) {
                        $executor->createObject($hit[1], $hit[2], $fixtures);
                    }

                    $value = $rm->getRepository($hit[1])->get($hit[2]);
                }
            });

        $fixtureData->setData($data);
        $object = $fixture->getConverter()->createObject($fixtureData);

        $fixtureData->setObject($object);
        $fixtureData->setOrder(++$this->order);

        unset($this->stack[$name . ':' . $key]);
    }

    /**
     *
     * @param array $data
     * @param FixtureType $fixtureType
     * @param string $objectType
     * @param string $objectKey
     * @throws \Exception
     */
    public function finalizeObject($fixtures, $name, $key)
    {

        $fixture = $fixtures[$name];
        $fixtureData = $fixture->getFixtureData($key);

        $executor = $this;
        $data = $fixtureData->getData();

        array_walk_recursive($data, function(&$value, $key) use ($executor, $fixtures) {

                if (!is_string($value)) {
                    return;
                }

                if (preg_match('/^@@(\w*):(\w*)$/', $value, $hit)) {

                    if(!isset($fixtures[$hit[1]]) || !$fixtures[$hit[1]]->getFixtureData($hit[2])) {
                        throw new \Exception();
                    }

                    $object = $fixtures[$hit[1]]->getFixtureData($hit[2])->getObject();

                    if(!$object) {
                        throw new \Exception();
                    }

                    $value = $object;
                }
            });

        $fixtureData->setData($data);
        $object = $fixtureData->getObject();

        $fixture->getConverter()->finalizeObject($object, $fixtureData);
    }

    /**
     *
     * @param array $fixtures
     * @throws \Exception
     */
    protected function checkFixtures(array $fixtures)
    {
        foreach($fixtures as $fixture) {
            if(!$fixture instanceof Fixture) {
                throw new \Exception();
            }
        }
    }

}