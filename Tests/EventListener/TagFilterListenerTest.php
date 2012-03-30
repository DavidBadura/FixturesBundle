<?php

namespace DavidBadura\FixturesBundle\Tests\EventListener;

use DavidBadura\FixturesBundle\EventListener\TagFilterListener;
use DavidBadura\FixturesBundle\Event\PreExecuteEvent;
use DavidBadura\FixturesBundle\FixtureBuilder;
use DavidBadura\FixturesBundle\FixtureCollection;

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
        $builder->setData(array());
        $builder->setConverter($this->converter);

        $fixture1 = $builder->setName('test1')->createFixture()->addTag('test')->addTag('install');
        $fixture2 = $builder->setName('test2')->createFixture()->addTag('test');
        $fixture3 = $builder->setName('test3')->createFixture()->addTag('install');
        $fixture4 = $builder->setName('test4')->createFixture();

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
