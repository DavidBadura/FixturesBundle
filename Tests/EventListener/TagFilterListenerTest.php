<?php

namespace DavidBadura\FixturesBundle\Tests\EventListener;

use DavidBadura\FixturesBundle\EventListener\TagFilterListener;
use DavidBadura\FixturesBundle\Event\PreExecuteEvent;
use DavidBadura\FixturesBundle\Tests\AbstractFixtureTest;
use DavidBadura\FixturesBundle\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TagFilterListenerTest extends AbstractFixtureTest
{

    /**
     *
     * @var type
     */
    protected $listener;

    public function setUp()
    {
        parent::setUp();
        $this->listener = new TagFilterListener();
    }

    public function testTagFilterListener()
    {
        $fixture1 = $this->createFixture('test1')->addTag('test')->addTag('install');
        $fixture2 = $this->createFixture('test2')->addTag('test');
        $fixture3 = $this->createFixture('test3')->addTag('install');
        $fixture4 = $this->createFixture('test4');

        $event = new PreExecuteEvent(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), array('tags' => array()));
        $this->listener->onPreExecute($event);
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), $event->getFixtures());

        $event = new PreExecuteEvent(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), array('tags' => array('install')));
        $this->listener->onPreExecute($event);
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture3)), $event->getFixtures());

        $event = new PreExecuteEvent(new FixtureCollection(array($fixture1, $fixture2, $fixture3, $fixture4)), array('tags' => array('install', 'test')));
        $this->listener->onPreExecute($event);
        $this->assertEquals(new FixtureCollection(array($fixture1, $fixture2, $fixture3)), $event->getFixtures());
    }

}
