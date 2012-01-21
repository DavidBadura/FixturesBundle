<?php

namespace DavidBadura\FixturesBundle\Tests\RelationManager;

use DavidBadura\FixturesBundle\RelationManager\RelationManager;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class RelationManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testRelationManager()
    {
        $rm = new RelationManager();
        $this->assertFalse($rm->hasRepository('type_name'));
        $repo = $rm->createRepository('type_name');
        $this->assertInstanceOf('DavidBadura\FixturesBundle\RelationManager\Repository', $repo);
        $this->assertEquals($repo, $rm->getRepository('type_name'));
    }

}