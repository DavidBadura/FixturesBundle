<?php

namespace DavidBadura\FixturesBundle\Tests\Executor;

use DavidBadura\FixturesBundle\Executor\Executor;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\RoleType;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class ExecutorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->rm = $this->getMock('DavidBadura\\FixturesBundle\\RelationManager\\RelationManagerInterface');
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

        $rm = clone $this->rm;
        $rm->expects($this->once())
            ->method('set')
            ->with($this->equalTo('role'), $this->equalTo('admin'), $this->isInstanceOf('Role'));

        $type = new RoleType();

        $executor = new Executor($this->rm, $this->persister);
        $executor->addFixtureType($type);
        $objects = $executor->execute($data);

        $this->assertCount(1, $objects);

        $object = array_shift($objects);

        $this->assertEquals('Admin', $object->name);

    }

    public function testObjectRelations()
    {
        $this->markTestIncomplete();
    }

}