<?php

namespace DavidBadura\FixturesBundle\Tests\Executor;

use DavidBadura\FixturesBundle\Executor\Executor;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

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
    public function testTreeValidationException()
    {

        $data = array(
            'user' => array(
                'david' => array(
                    'undefine' => 'test'
                )
            )
        );

        $type = $this->getMock('DavidBadura\\FixturesBundle\\FixtureType\\FixtureType');

        $type->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('user'));


        $executor = new Executor($this->rm, $this->persister);
        $executor->addFixtureType($type);
        $executor->execute($data);
    }

    public function testCreateObject()
    {
        $this->markTestIncomplete();
    }

    public function testObjectRelations()
    {
        $this->markTestIncomplete();
    }

}