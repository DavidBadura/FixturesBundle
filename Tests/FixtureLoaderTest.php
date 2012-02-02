<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\RelationManager\RelationManager;
use DavidBadura\FixturesBundle\FixtureLoader;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\RoleType;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\UserType;
use DavidBadura\FixturesBundle\Tests\TestFixtureTypes\GroupType;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureLoaderTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyData()
    {
        $rm = new RelationManager();
        $loader = new FixtureLoader($rm);
        $loader->loadFixtures(array(), array());

        $this->assertCount(0, $rm);
    }

    public function testEmptyTypes()
    {
        $data = array(
            'test' => array(
                'key' => array()
            )
        );

        $rm = new RelationManager();
        $loader = new FixtureLoader($rm);
        $loader->loadFixtures($data, array());

        $this->assertCount(0, $rm);
        $this->assertFalse($rm->hasRepository('test'));
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

        $type = new RoleType();

        $rm = new RelationManager();
        $loader = new FixtureLoader($rm);
        $loader->loadFixtures($data, array($type), array('no-persist' => true));

        $this->assertCount(1, $rm);
        $this->assertTrue($rm->hasRepository('role'));

        $repo = $rm->getRepository('role');
        $this->assertTrue($repo->has('admin'));

        $object = $repo->get('admin');
        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\Role', $object);
        $this->assertEquals('Admin', $object->name);
    }

    public function testObjectRelations()
    {

        $data = array(
            'role' => array(
                'admin' => array(
                    'name' => 'Admin'
                )
            ),
            'user' => array(
                'david' => array(
                    'name' => 'David Badura',
                    'email' => 'd.badura@gmx.de',
                    'roles' => array(
                        '@role:admin'
                    )
                )
            )
        );

        $roleType = new RoleType();
        $userType = new UserType();

        $rm = new RelationManager();
        $loader = new FixtureLoader($rm);
        $loader->loadFixtures($data, array($roleType, $userType), array('no-persist' => true));

        $this->assertCount(2, $rm);
        $this->assertTrue($rm->hasRepository('role'));
        $this->assertTrue($rm->hasRepository('user'));

        $roleRepo = $rm->getRepository('role');
        $userRepo = $rm->getRepository('user');

        $this->assertCount(1, $roleRepo);
        $this->assertCount(1, $userRepo);

        $this->assertTrue($roleRepo->has('admin'));
        $this->assertTrue($userRepo->has('david'));

        $role = $roleRepo->get('admin');
        $user = $userRepo->get('david');

        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\Role', $role);
        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\User', $user);

        $this->assertEquals(array($role), $user->roles);
    }

    public function testBidirectionalRelation()
    {

        $data = array(
            'group' => array(
                'developer' => array(
                    'leader' => '@@user:david',
                    'name' => 'Admin'
                )
            ),
            'user' => array(
                'david' => array(
                    'name' => 'David Badura',
                    'email' => 'd.badura@gmx.de',
                    'groups' => array(
                        '@group:developer'
                    )
                )
            )
        );

        $groupType = new GroupType();
        $userType = new UserType();

        $rm = new RelationManager();
        $loader = new FixtureLoader($rm);
        $loader->loadFixtures($data, array($groupType, $userType), array('no-persist' => true));

        $this->assertCount(2, $rm);
        $this->assertTrue($rm->hasRepository('group'));
        $this->assertTrue($rm->hasRepository('user'));

        $groupRepo = $rm->getRepository('group');
        $userRepo = $rm->getRepository('user');

        $this->assertCount(1, $groupRepo);
        $this->assertCount(1, $userRepo);

        $this->assertTrue($groupRepo->has('developer'));
        $this->assertTrue($userRepo->has('david'));

        $group = $groupRepo->get('developer');
        $user = $userRepo->get('david');

        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\Group', $group);
        $this->assertInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\User', $user);

        $this->assertEquals(array($group), $user->groups);
        $this->assertEquals($user, $group->leader);
    }

    public function testPersistObject()
    {
        $data = array(
            'role' => array(
                'admin' => array(
                    'name' => 'Admin'
                )
            )
        );

        $type = new RoleType();

        $rm = new RelationManager();
        $loader = new FixtureLoader($rm);

        $persister = $this->getMock('DavidBadura\FixturesBundle\Persister\PersisterInterface');
        $persister->expects($this->once())
            ->method('addObject')
            ->with($this->isInstanceOf('DavidBadura\FixturesBundle\Tests\TestObjects\Role'));

        $persister->expects($this->once())
            ->method('save');

        $loader->addPersister('test-persister', $persister);
        $loader->loadFixtures($data, array($type));
    }

}