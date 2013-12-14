<?php

namespace DavidBadura\FixturesBundle\Tests\EventListener;

use DavidBadura\FixturesBundle\Tests\AbstractFixtureTest;
use DavidBadura\FixturesBundle\Tests\TestObjects\User;
use DavidBadura\FixturesBundle\EventListener\SecurityListener;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use DavidBadura\Fixtures\Event\FixtureCollectionEvent;
use DavidBadura\Fixtures\Fixture\FixtureCollection;
use DavidBadura\Fixtures\Fixture\Fixture;
use DavidBadura\Fixtures\Fixture\FixtureData;
use DavidBadura\Fixtures\Converter\FixtureConverterInterface;

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
        $this->fixtureManager = $this->getMock('DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface');
        $this->encoder = $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        $this->encoder->expects($this->any())->method('encodePassword')->will($this->returnCallback(function($password, $salt) {
            return $password . $salt . 'hash';
        }));

        $this->factory = $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface');
        $this->factory->expects($this->any())->method('getEncoder')->will($this->returnValue($this->encoder));

        $this->listener = new SecurityListener($this->factory);

        $this->converterMock = $this->getMock('DavidBadura\FixturesBundle\FixtureConverter\FixtureConverterInterface');
        Fixture::setDefaultConverter($this->converterMock);
    }

    public function testEnabledSecurityListener()
    {
        $fixture = Fixture::create('user', array(
            'data' => array(),
            'properties' => array(
                'security' => true
            )
        ));
 
        $user = new User('test-user', 'test@localhost');
        $user->setPassword('132');

        $data = new FixtureData('test_user', array());
        $data->setObject($user);

        $fixture->add($data);

        $fixtures = new FixtureCollection(array(
            $fixture
        ));

        $event = new FixtureCollectionEvent($this->fixtureManager, $fixtures, array());
        $this->listener->onPostExecute($event);

        $this->assertEquals('132secrethash', $user->getPassword());
    }

    public function testDisabledSecurityListener()
    {
        $fixture = Fixture::create('user', array(
            'data' => array(),
            'properties' => array(
                'security' => false
            )
        ));

        $user = new User('test-user', 'test@localhost');
        $user->setPassword('132');

        $data = new FixtureData('test_user', array());
        $data->setObject($user);

        $fixture->add($data);

        $fixtures = new FixtureCollection(array(
            $fixture
        ));

        $event = new FixtureCollectionEvent($this->fixtureManager, $fixtures, array());
        $this->listener->onPostExecute($event);

        $this->assertEquals('132', $user->getPassword());
    }

    public function testSecurityListenerOtherFields()
    {
        $user = new User('test-user', 'test@localhost');
        $user->setPassword('132');
        
        $fixture = Fixture::create('user', array(
            'data' => array(),
            'properties' => array(
                'security' => array(
                    'password' => 'name',
                    'salt' => 'password'
                )
            )
        ));

        $data = new FixtureData('test_user', array());
        $data->setObject($user);

        $fixture->add($data);

        $fixtures = new FixtureCollection(array(
            $fixture
        ));

        $event = new FixtureCollectionEvent($this->fixtureManager, $fixtures, array());
        $this->listener->onPostExecute($event);

        $this->assertEquals('test-user132hash', $user->getName());
    }

}
