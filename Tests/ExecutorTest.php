<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\Executor;
use DavidBadura\FixturesBundle\FixtureConverter\DefaultConverter;
use DavidBadura\FixturesBundle\Fixture;
use DavidBadura\FixturesBundle\FixtureData;
use DavidBadura\FixturesBundle\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ExecutorTest extends AbstractFixtureTest
{

    /**
     *
     * @var FixtureLoader
     */
    private $executor;

    public function setUp()
    {
        parent::setUp();
        $this->executor = new Executor();
    }

    public function testExecute()
    {
        $userFixture = $this->createUserFixture(array(
            'david' => array(
                'name' => 'David Badura',
                'email' => 'd.badura@gmx.de',
                'groups' => array('@group:users'),
                'roles' => array('@role:admin', '@role:dev')
            ),
            'test' => array(
                'name' => 'test',
                'email' => 'test@example.de',
                'groups' => array('@group:users'),
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

        $fixtures = new FixtureCollection(array($userFixture, $groupFixture, $roleFixture));
        $this->executor->execute($fixtures);

        $david = $userFixture->getFixtureData('david')->getObject();

        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\User', $david);
        $this->assertEquals('David Badura', $david->getName());

        $groups = $david->getGroups();
        $this->assertEquals(1, count($groups));
        $this->assertEquals('Users', $groups[0]->name);
        $this->assertEquals($david, $groups[0]->leader);
    }

}