<?php

namespace DavidBadura\FixturesBundle;

use DavidBadura\FixturesBundle\Event\PreExecuteEvent;
use DavidBadura\FixturesBundle\Event\PostExecuteEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use DavidBadura\FixturesBundle\Executor\ExecutorInterface;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureManager
{

    /**
     *
     * @var FixtureLoader
     */
    private $fixtureLoader;

    /**
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     *
     * @var ExecutorInterface
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
    public function __construct(FixtureLoader $fixtureLoader,
        FixtureFactory $fixtureFactory, ExecutorInterface $executor,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->fixtureLoader = $fixtureLoader;
        $this->fixtureFactory = $fixtureFactory;
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
     * @return FixtureFactory
     */
    public function getFixtureFactory()
    {
        return $this->fixtureFactory;
    }

    /**
     *
     * @return ExecutorInterface
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     *
     * @param array $options
     */
    public function load(array $options = array())
    {
        $data = $this->fixtureLoader->loadFixtures(($options['fixtures']));
        $fixtures = $this->fixtureFactory->createFixtures($data);

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

