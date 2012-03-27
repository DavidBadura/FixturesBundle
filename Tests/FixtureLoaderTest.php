<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\FixtureLoader;
use DavidBadura\FixturesBundle\ConverterRepository;
use DavidBadura\FixturesBundle\FixtureConverter\DefaultConverter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FixtureLoader
     */
    private $loader;

    private $converter;

    public function setUp()
    {
        $this->converter = new DefaultConverter();

        $repo = new ConverterRepository();
        $repo->addConverter($this->converter);

        $this->loader = new FixtureLoader($repo);
    }

    public function testLoadFixturesByPath()
    {
        $fixtures = $this->loader->loadFixtures(__DIR__ . '/TestResources/fixtures');

        $this->assertEquals(3, count($fixtures));

        $this->assertEquals('user', $fixtures['user']->getName());
        $this->assertEquals('group', $fixtures['group']->getName());
        $this->assertEquals('role', $fixtures['role']->getName());

        $this->assertEquals($this->converter, $fixtures['user']->getConverter());
        $this->assertEquals($this->converter, $fixtures['group']->getConverter());
        $this->assertEquals($this->converter, $fixtures['role']->getConverter());
    }

}