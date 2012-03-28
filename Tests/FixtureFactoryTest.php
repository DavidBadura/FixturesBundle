<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\FixtureFactory;
use DavidBadura\FixturesBundle\ConverterRepository;
use DavidBadura\FixturesBundle\FixtureConverter\DefaultConverter;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FixtureFactory
     */
    private $factory;

    /**
     *
     * @var DefaultConverter
     */
    private $converter;

    public function setUp()
    {
        $this->converter = new DefaultConverter();

        $repo = new ConverterRepository();
        $repo->addConverter($this->converter);

        $this->factory = new FixtureFactory($repo);
    }

    public function testLoadFixturesByPath()
    {

        $data = array(
            'user' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\FixturesBundle\\Tests\\TestObjects\\User',
                    'constructor' =>
                    array(
                        0 => 'name',
                        1 => 'email',
                    ),
                ),
                'data' =>
                array(
                    'david' =>
                    array(
                        'name' => 'David Badura',
                        'email' => 'd.badura@gmx.de',
                        'group' =>
                        array(
                            0 => '@group:owner',
                            1 => '@group:developer',
                        ),
                        'role' =>
                        array(
                            0 => '@role:admin',
                        ),
                    ),
                    'other' =>
                    array(
                        'name' => 'Somebody',
                        'email' => 'test@example.de',
                        'group' =>
                        array(
                            0 => '@group:developer',
                        ),
                        'role' =>
                        array(
                            0 => '@role:user',
                        ),
                    ),
                ),
            ),
            'group' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\FixturesBundle\\Tests\\TestObjects\\Group',
                ),
                'data' =>
                array(
                    'developer' =>
                    array(
                        'name' => 'Developer',
                        'leader' => '@@user:david',
                    ),
                ),
            ),
            'role' =>
            array(
                'properties' =>
                array(
                    'class' => 'DavidBadura\\FixturesBundle\\Tests\\TestObjects\\Role',
                ),
                'data' =>
                array(
                    'admin' =>
                    array(
                        'name' => 'Admin',
                    ),
                    'user' =>
                    array(
                        'name' => 'User',
                    ),
                ),
            ),
        );

        $fixtures = $this->factory->createFixtures($data);

        $this->assertEquals(3, count($fixtures));

        $this->assertEquals('user', $fixtures['user']->getName());
        $this->assertEquals('group', $fixtures['group']->getName());
        $this->assertEquals('role', $fixtures['role']->getName());

        $this->assertEquals($this->converter, $fixtures['user']->getConverter());
        $this->assertEquals($this->converter, $fixtures['group']->getConverter());
        $this->assertEquals($this->converter, $fixtures['role']->getConverter());
    }

}