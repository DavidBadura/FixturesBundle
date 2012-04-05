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
        $this->validator = $this->getMock('Symfony\Component\Validator\ValidatorInterface');
        $this->validator->expects($this->never())->method('validate')->will($this->returnValue(array()));

        $this->listener = new ValidationListener($this->validator);
    }

    public function testValidationListener()
    {
        $fixtures = new FixtureCollection(array(
            $this->createFixture('test1', array('key1' => 'data1')),
            $this->createFixture('test2', array('key2' => 'data2'))
        ));

        $event = new PostExecuteEvent($fixtures, array());
        $this->listener->onPostExecute($event);
    }

}
