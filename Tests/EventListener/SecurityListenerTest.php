<?php

namespace DavidBadura\FixturesBundle\Tests\EventListener;

use DavidBadura\FixturesBundle\EventListener\SecurityListener;
use DavidBadura\FixturesBundle\Event\PostExecuteEvent;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use DavidBadura\FixturesBundle\FixtureCollection;
use DavidBadura\FixturesBundle\Fixture;
use DavidBadura\FixturesBundle\FixtureData;
use DavidBadura\FixturesBundle\Tests\AbstractFixtureTest;
use DavidBadura\FixturesBundle\FixtureConverter\FixtureConverterInterface;
use DavidBadura\FixturesBundle\Tests\TestObjects\User;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class SecurityListenerTest extends AbstractFixtureTest
{

    /**
     * @var EncoderFactoryInterface
     */
    private $factory;

    /**
     *
     * @var PasswordEncoderInterface
     */
    private $encoder;

    /**
     *
     * @var PersistListener
     */
    private $listener;

    /**
     *
     * @var FixtureConverterInterface
     */
    private $converterMock;

    public function setUp()
    {
        parent::setUp();
        $this->encoder = $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        $this->encoder->expects($this->any())->method('encodePassword')->will($this->returnCallback(function($password, $salt) {
            return $password . $salt . 'hash';
        }));

        $this->factory = $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface');
        $this->factory->expects($this->any())->method('getEncoder')->will($this->returnValue($this->encoder));

        $this->listener = new SecurityListener($this->factory);

        $this->converterMock = $this->getMock('DavidBadura\FixturesBundle\FixtureConverter\FixtureConverterInterface');
    }

    public function testEnabledSecurityListener()
    {
        $user = new User('test-user', 'test@localhost');
        $user->setPassword('132');

        $data = new FixtureData('test_user', array());
        $data->setObject($user);

        $fixture = new Fixture('user', $this->converterMock);
        $fixture->addFixtureData($data);

        $fixture->setProperties(array(
            'security' => true
        ));

        $fixtures = new FixtureCollection(array(
            $fixture
        ));

        $event = new PostExecuteEvent($fixtures, array());
        $this->listener->onPostExecute($event);

        $this->assertEquals('132secrethash', $user->getPassword());
    }

    public function testDisabledSecurityListener()
    {
        $user = new User('test-user', 'test@localhost');
        $user->setPassword('132');

        $data = new FixtureData('test_user', array());
        $data->setObject($user);

        $fixture = new Fixture('user', $this->converterMock);
        $fixture->addFixtureData($data);

        $fixture->setProperties(array(
            'security' => false
        ));

        $fixtures = new FixtureCollection(array(
            $fixture
        ));

        $event = new PostExecuteEvent($fixtures, array());
        $this->listener->onPostExecute($event);

        $this->assertEquals('132', $user->getPassword());
    }

    public function testSecurityListenerOtherFields()
    {
        $user = new User('test-user', 'test@localhost');
        $user->setPassword('132');

        $data = new FixtureData('test_user', array());
        $data->setObject($user);

        $fixture = new Fixture('user', $this->converterMock);
        $fixture->addFixtureData($data);

        $fixture->setProperties(array(
            'security' => array(
                'password' => 'name',
                'salt' => 'password'
            )
        ));

        $fixtures = new FixtureCollection(array(
            $fixture
        ));

        $event = new PostExecuteEvent($fixtures, array());
        $this->listener->onPostExecute($event);

        $this->assertEquals('test-user132hash', $user->getName());
    }


}
