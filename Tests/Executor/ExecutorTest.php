<?php

namespace DavidBadura\FixturesBundle\Tests\Executor;

use DavidBadura\FixturesBundle\Executor\Executor;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use DavidBadura\FixturesBundle\RelationManager\RelationManager;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\Role;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\RoleType;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\User;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\UserType;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ExecutorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->rm = new RelationManager();
        $this->persister = $this->getMock('DavidBadura\\FixturesBundle\\Persister\\PersisterInterface');
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTreeValidationUnknownTypeException()
    {

        $data = array(
            'undefine' => array()
        );

        $type = new RoleType();

        $executor = new Executor($this->rm, $this->persister);
        $executor->addFixtureType($type);
        $executor->execute($data);
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testTreeValidationUnknownPropertyException()
    {

        $data = array(
            'role' => array(
                'admin' => array(
                    'undefine' => 'test'
                )
            )
        );

        $type = new RoleType();

        $executor = new Executor($this->rm, $this->persister);
        $executor->addFixtureType($type);
        $executor->execute($data);
    }

    public function testCreateObject()
    {

        $data = array(
            'role' => array(
                'admin' => array(
                    'name' => 'Admin'
                )
            )
        );

        $type = new RoleType();

        $executor = new Executor(clone $this->rm, $this->persister);
        $executor->addFixtureType($type);
        $objects = $executor->execute($data);

        $this->assertCount(1, $objects);

        $object = array_shift($objects);

        $this->assertEquals('Admin', $object->name);

    }

    public function testObjectRelations()
    {

        $data = array(
            'role' => array(
                'admin' => array(
                    'name' => 'Admin'
                )
            ),
            'user' => array(
                'david' => array(
                    'name' => 'David Badura',
                    'email' => 'd.badura@gmx.de',
                    'roles' => array(
                        '@role:admin'
                    )
                )
            )
        );

        $executor = new Executor(clone $this->rm, $this->persister);
        $executor->addFixtureType(new RoleType());
        $executor->addFixtureType(new UserType());
        $objects = $executor->execute($data);

        $this->assertCount(2, $objects);

        $rm = $executor->getRelationManager();

        $this->assertTrue($rm->has('user', 'david'));
        $this->assertTrue($rm->has('role', 'admin'));

        $user = $rm->get('user', 'david');
        $role = $rm->get('role', 'admin');

        $this->assertEquals(array($role), $user->roles);

    }

}