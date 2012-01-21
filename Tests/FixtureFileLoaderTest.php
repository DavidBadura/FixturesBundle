<?php

namespace DavidBadura\FixturesBundle\Tests\RelationManager;

use DavidBadura\FixturesBundle\FixtureFileLoader;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureFileLoaderTest extends \PHPUnit_Framework_TestCase
{

    public function testLoadData()
    {
        $draft = array(
            'user' => array(
                'david' => array(
                    'name' => 'David Badura',
                    'group' => array('@group:owner', '@group:developer'),
                    'role' => array('@role:admin'),
                ),
                'other' => array(
                    'name' => 'Somebody',
                    'group' => array('@group:developer'),
                    'role' => array('@role:user'),
                ),
            ),
            'group' => array(
                'developer' => array(
                    'name' => 'Developer',
                    'leader' => '@@user:david',
                ),
            ),
            'role' => array(
                'admin' => array(
                    'name' => 'Admin',
                ),
                'user' => array(
                    'name' => 'User',
                ),
            ),
        );

        $loader = new FixtureFileLoader();

        $data = $loader->loadFixtureData(array(
            __DIR__ . '/TestResources/fixtures'
            ));

        $this->assertEquals($draft, $data);
    }

}