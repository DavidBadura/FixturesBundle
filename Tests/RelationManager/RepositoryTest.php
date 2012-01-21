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

        $object = new stdClass();


    }

}