<?php

namespace DavidBadura\FixturesBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DavidBadura\FixturesBundle\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PostExecuteEvent extends Event
{

    /**
     *
     * @var FixtureCollection
     */
    private $fixtures;

    /**
     *
     * @var array
     */
    private $options;

    /**
     *
     * @param FixtureCollection $fixtures
     * @param array             $options
     */
    public function __construct(FixtureCollection $fixtures, array $options = array())
    {
        $this->fixtures = $fixtures;
        $this->options = $options;
    }

    /**
     *
     * @return FixtureCollection
     */
    public function getFixtures()
    {
        return $this->fixtures;
    }

    /**
     *
     * @param  FixtureCollection                                  $fixtures
     * @return \DavidBadura\FixturesBundle\Event\PostExecuteEvent
     */
    public function setFixtures(FixtureCollection $fixtures)
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
     * @param  array                                              $options
     * @return \DavidBadura\FixturesBundle\Event\PostExecuteEvent
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

}
