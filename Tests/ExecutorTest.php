<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\Executor;
use DavidBadura\FixturesBundle\FixtureConverter\DefaultConverter;
use DavidBadura\FixturesBundle\FixtureBuilder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ExecutorTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var FixtureLoader
     */
    private $executor;

    private $converter;

    public function setUp()
    {
        $this->executor = new Executor();
        $this->converter = new DefaultConverter();
    }

    public function testExecute()
    {
        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'group' => '@group:users',
                'roles' => array('@role:admin', '@role:dev')
            ),
            'test' => array(
                'name' => 'test',
                'email' => 'test@example.de',
                'group' => '@group:users',
                'roles' => array('@role:dev')
            )
        ));

        $groupFixture = $this->createGroupFixture(array(
            'users' => array(
                'name' => 'Users',
                'leader' => '@@user:david'
            )
        ));

        $roleFixture = $this->createRoleFixture(array(
            'admin' => array(
                'name' => 'Admin'
            ),
            'dev' => array(
                'name' => 'Developer'
            )
        ));

        $this->executor->execute(array($userFixture, $groupFixture, $roleFixture));
    }


    protected function createUserFixture($data)
    {
        $builder = new FixtureBuilder();
        $builder->setName('user')
                ->setConverter($this->converter)
                ->setData($data)
                ->setProperties(array(
                    'class' => 'DavidBadura\FixturesBundle\Tests\TestObjects\User',
                    'constructor' => array('name', 'email')
                ))
        ;
        return $builder->createFixture();
    }

    protected function createGroupFixture($data)
    {
        $builder = new FixtureBuilder();
        $builder->setName('group')
                ->setConverter($this->converter)
                ->setData($data)
                ->setProperties(array(
                    'class' => 'DavidBadura\FixturesBundle\Tests\TestObjects\Group'
                ))
        ;
        return $builder->createFixture();
    }

    protected function createRoleFixture($data)
    {
        $builder = new FixtureBuilder();
        $builder->setName('role')
                ->setConverter($this->converter)
                ->setData($data)
                ->setProperties(array(
                    'class' => 'DavidBadura\FixturesBundle\Tests\TestObjects\Role'
                ))
        ;
        return $builder->createFixture();
    }

}