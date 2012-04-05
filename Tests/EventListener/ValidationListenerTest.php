<?php

namespace DavidBadura\FixturesBundle\Tests\EventListener;

use DavidBadura\FixturesBundle\EventListener\ValidationListener;
use DavidBadura\FixturesBundle\Event\PostExecuteEvent;
use Symfony\Component\Validator\ValidatorInterface;
use DavidBadura\FixturesBundle\FixtureCollection;
use DavidBadura\FixturesBundle\Tests\AbstractFixtureTest;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ValidationListenerTest extends AbstractFixtureTest
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     * @var PersistListener
     */
    private $listener;

    public function setUp()
    {
        parent::setUp();
        $this->persister = $this->getMock('Symfony\Component\Validator\ValidatorInterface');
        $this->persister->expects($this->exactly(2))->method('addObject');
        $this->persister->expects($this->once())->method('save');

        $this->listener = new PersistListener($this->persister);
    }

    public function testPersistListener()
    {
        $fixtures = new FixtureCollection(array(
            $this->createFixture('test1', array('key1' => 'data1')),
            $this->createFixture('test2', array('key2' => 'data2'))
        ));

        $event = new PostExecuteEvent($fixtures, array());
        $this->listener->onPostExecute($event);
    }

}
