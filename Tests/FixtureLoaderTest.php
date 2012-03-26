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

    public function setUp()
    {
        $repo = new ConverterRepository();
        $repo->addConverter(new DefaultConverter());

        $this->loader = new FixtureLoader($repo);
    }

    public function testLoadFixturesByPath()
    {
        $fixtures = $this->loader->loadFixtures(__DIR__ . '/TestResources/fixtures');

        $this->assertCount(3, $fixtures);

        $this->assertEquals('user', $fixtures['user']->getName());
        $this->assertEquals('group', $fixtures['group']->getName());
        $this->assertEquals('role', $fixtures['role']->getName());
    }

}