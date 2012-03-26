<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\FixtureBuilder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureBuilderTest extends \PHPUnit_Framework_TestCase
{

    protected $converter;

    public function setUp()
    {
        $this->converter = $this->getMock('\DavidBadura\FixturesBundle\FixtureConverter\FixtureConverterInterface');
    }

    public function testFixtureBuilderSetterGetter()
    {
        $data = array('test_data' => array(1,2,3));

        $builder = new FixtureBuilder();
        $builder->setName('test_name');
        $builder->setConverter($this->converter);
        $builder->setTags(array('test_tag_1', 'test_tag_2'));
        $builder->addTag('test_tag_3');
        $builder->setEnableValidation(true);
        $builder->setValidationGroup('test_group');
        $builder->setData($data);

        $this->assertEquals('test_name', $builder->getName());
        $this->assertEquals($this->converter, $builder->getConverter());
        $this->assertEquals(array('test_tag_1', 'test_tag_2', 'test_tag_3'), $builder->getTags());
        $this->assertTrue($builder->isEnableValidation());
        $this->assertEquals('test_group', $builder->getValidationGroup());
    }

    public function testFixtureBuilderCreate()
    {
        $data = array('test_data' => array(1,2,3));

        $builder = new FixtureBuilder();
        $builder->setName('test_name');
        $builder->setConverter($this->converter);
        $builder->setTags(array('test_tag_1', 'test_tag_2'));
        $builder->addTag('test_tag_3');
        $builder->setEnableValidation(true);
        $builder->setValidationGroup('test_group');
        $builder->setData($data);

        $fixture = $builder->createFixture();

        $this->assertEquals('test_name', $fixture->getName());
        $this->assertEquals($this->converter, $fixture->getConverter());
        $this->assertEquals(array('test_tag_1', 'test_tag_2', 'test_tag_3'), $fixture->getTags());
        $this->assertTrue($fixture->isEnableValidation());
        $this->assertEquals('test_group', $fixture->getValidationGroup());
    }

}