<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\DavidBaduraFixturesBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DavidBaduraFixturesBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $container = new ContainerBuilder();
        $bundle = new DavidBaduraFixturesBundle();

        $bundle->build($container);
    }
}
