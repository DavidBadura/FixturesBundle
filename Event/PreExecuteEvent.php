<?php

namespace DavidBadura\FixturesBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PreExecuteEvent extends Event
{

    /**
     *
     * @var array
     */
    private $fixtures;

    /**
     *
     * @var array
     */
    private $options;

    /**
     *
     * @param array $fixtures
     * @param array $options
     */
    public function __construct(array $fixtures, array $options = array())
    {
        $this->fixtures = $fixtures;
        $this->options = $options;
    }

    /**
     *
     * @return array
     */
    public function getFixtures()
    {
        return $this->fixtures;
    }

    /**
     *
     * @param array $fixtures
     * @return \DavidBadura\FixturesBundle\Event\PreExecuteEvent
     */
    public function setFixtures(array $fixtures)
    {
        $this->fixtures = $fixtures;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @param array $options
     * @return \DavidBadura\FixturesBundle\Event\PreExecuteEvent
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

}

