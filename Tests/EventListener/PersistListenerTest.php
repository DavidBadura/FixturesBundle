<?php

namespace DavidBadura\FixturesBundle\Tests\EventListener;

use DavidBadura\FixturesBundle\EventListener\PersistListener;
use DavidBadura\FixturesBundle\Event\PostExecuteEvent;
use DavidBadura\FixturesBundle\Persister\PersisterInterface;
use DavidBadura\FixturesBundle\FixtureBuilder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class PersistListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PersisterInterface
     */
    private $persister;

    /**
     *
     * @var PersistListener
     */
    private $listener;

    /**
     *
     * @var DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter
     */
    private $converter;

    public function setUp()
    {
        $this->persister = $this->getMock('DavidBadura\FixturesBundle\Persister\PersisterInterface');
        $this->persister->expects($this->exactly(2))->method('addObject');
        $this->persister->expects($this->once())->method('save');

        $this->listener = new PersistListener($this->persister);

        $this->converter = $this->getMock('DavidBadura\FixturesBundle\FixtureConverter\FixtureConverter');
    }

    public function testPersistListener()
    {
        $builder = new FixtureBuilder();
        $builder->setName('test');
        $builder->setData(array(array('test')));
        $builder->setConverter($this->converter);

        $event = new PostExecuteEvent(array(
            $builder->createFixture(),
            $builder->createFixture()
        ), array());
        $this->listener->onPostExecute($event);
    }

}
