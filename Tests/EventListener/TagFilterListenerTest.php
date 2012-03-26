<?php

namespace DavidBadura\FixturesBundle\Tests\EventListener;

use DavidBadura\FixturesBundle\EventListener\TagFilterListener;
use DavidBadura\FixturesBundle\Event\PreExecuteEvent;
use DavidBadura\FixturesBundle\FixtureBuilder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class TagFilterListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var type
     */
    protected $listener;

    /**
     *
     * @var DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->listener = new TagFilterListener();
        $this->converter = $this->getMock('DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter');
    }

    public function testTagFilterListener()
    {
        $builder = new FixtureBuilder();
        $builder->setName('test');
        $builder->setData(array());
        $builder->setConverter($this->converter);

        $fixture1 = $builder->createFixture()->addTag('test')->addTag('install');
        $fixture2 = $builder->createFixture()->addTag('test');
        $fixture3 = $builder->createFixture()->addTag('install');
        $fixture4 = $builder->createFixture();

        $fixtures = array($fixture1, $fixture2, $fixture3, $fixture4);

        $event = new PreExecuteEvent($fixtures, array('tags' => array()));
        $this->listener->onPreExecute($event);
        $this->assertEquals($fixtures, $event->getFixtures());

        $event = new PreExecuteEvent($fixtures, array('tags' => array('install')));
        $this->listener->onPreExecute($event);
        $this->assertEquals(array($fixture1, $fixture3), $event->getFixtures());

        $event = new PreExecuteEvent($fixtures, array('tags' => array('install', 'test')));
        $this->listener->onPreExecute($event);
        $this->assertEquals(array($fixture1, $fixture2, $fixture3), $event->getFixtures());
    }

}
