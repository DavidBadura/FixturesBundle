<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter;
use DavidBadura\FixturesBundle\Event\PreExecuteEvent;
use DavidBadura\FixturesBundle\Event\PostExecuteEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManager
{

    /**
     *
     * @var FixtureConverter[]
     */
    private $converters = array();

    /**
     *
     * @var FixtureFactory
     */
    private $fixtureLoader;

    /**
     *
     * @var Executor
     */
    private $executor;

    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     *
     * @param PersisterInterface $persister
     */
    public function __construct(FixtureFactory $fixtureLoader,
        Executor $executor, EventDispatcherInterface $eventDispatcher)
    {
        $this->fixtureLoader = $fixtureLoader;
        $this->executor = $executor;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     *
     * @return FixtureFactory
     */
    public function getFixtureLoader()
    {
        return $this->fixtureLoader;
    }

    /**
     *
     * @return Executor
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     *
     * @param FixtureConverter $converter
     * @return \DavidBadura\FixturesBundle\FixtureManager
     * @throws \Exception
     */
    public function addConverter(FixtureConverter $converter)
    {
        $name = $converter->getName();
        if (isset($this->converters[$name])) {
            throw new \Exception();
        }

        $this->converters[$name] = $converter;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function hasConverter($name)
    {
        return isset($this->converters[$name]);
    }

    /**
     *
     * @param string $name
     * @return FixtureConverter
     * @throws \Exception
     */
    public function getConverter($name)
    {
        if (!$this->hasConverter($name)) {
            throw new \Exception();
        }

        return $this->converters[$name];
    }

    /**
     *
     * @param string $name
     * @return \DavidBadura\FixturesBundle\FixtureManager
     * @throws \Exception
     */
    public function removeConverter($name)
    {
        if (!$this->hasConverter($name)) {
            throw new \Exception();
        }

        unset($this->converters[$name]);
        return $this;
    }

    /**
     *
     * @param array $options
     */
    public function load(array $options = array())
    {
        $fixtures = $this->factory->loadFixtures(($options['fixtures']) ?: null);

        $event = new PreExecuteEvent($fixtures, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPreExecute, $event);

        $fixtures = $event->getFixtures();
        $options = $event->getOptions();

        $this->executor->execute($fixtures);

        $event = new PostExecuteEvent($fixtures, $options);
        $this->eventDispatcher->dispatch(FixtureEvents::onPostExecute, $event);

        $fixtures = $event->getFixtures();
        $options = $event->getOptions();

        return $fixtures;
    }

}

