<?php

namespace DavidBadura\FixturesBundle\Tests\RelationManager;

use DavidBadura\FixturesBundle\RelationManager\Repository;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{

    public function testRepository()
    {

        $repo = new Repository();
        $this->assertCount(0, $repo);
        $this->assertFalse($repo->has('any_object'));

        $object1 = new  \stdClass();

        $repo->set('any_object', $object1);
        $this->assertCount(1, $repo);
        $this->assertTrue($repo->has('any_object'));
        $this->assertEquals($object1, $repo->get('any_object'));

        $object2 = new  \stdClass();

        $repo->set('other_object', $object2);
        $this->assertCount(2, $repo);
        $this->assertTrue($repo->has('any_object'));
        $this->assertTrue($repo->has('other_object'));
        $this->assertEquals($object1, $repo->get('any_object'));
        $this->assertEquals($object2, $repo->get('other_object'));

    }

}