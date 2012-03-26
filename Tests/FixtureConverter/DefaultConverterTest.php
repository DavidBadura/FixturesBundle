<?php

namespace DavidBadura\FixturesBundle\Tests\FixtureConverter;

use DavidBadura\FixturesBundle\FixtureData;
use DavidBadura\FixturesBundle\FixtureConverter\DefaultConverter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DefaultConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var DefaultConverter
     */
    protected $converter;

    public function setUp()
    {
        $this->converter = new DefaultConverter();
    }

    public function testDefaultConverterCreateObject()
    {

        $data = $this->getMock('DavidBadura\FixturesBundle\FixtureData', array('getProperties'), array(
            'test',
            array(
                'name' => 'test_name',
                'email' => 'test_email',
                'groups' => array('xyz', 'abc')
            )
        ));

        $data->expects($this->any())->method('getProperties')->will($this->returnValue(array(
            'class' => 'DavidBadura\FixturesBundle\Tests\TestObjects\User',
            'constructor' => array('name', 'email')
        )));

        $object = $this->converter->createObject($data);

        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\User', $object);
        $this->assertEquals('test_name', $object->getName());
        $this->assertEquals('test_email', $object->getEmail());
        $this->assertEquals(array('xyz', 'abc'), $object->getGroups());
    }
    
}