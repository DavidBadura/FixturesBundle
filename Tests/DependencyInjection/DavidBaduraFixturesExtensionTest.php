<?php

namespace DavidBadura\FixturesBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use DavidBadura\FixturesBundle\DependencyInjection\DavidBaduraFixturesExtension;
use Symfony\Component\Yaml\Parser;

class DavidBaduraFixturesExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testLoadServices()
    {
        $containerBuilder = new ContainerBuilder();
        $loader = new DavidBaduraFixturesExtension();
        $loader->load(array(), $containerBuilder);

        $this->assertTrue($containerBuilder->has('davidbadura_fixtures.fixture_loader'));
        $this->assertTrue($containerBuilder->has('davidbadura_fixtures.relation_manager'));
        $this->assertTrue($containerBuilder->has('davidbadura_fixtures.fixture_type_loader'));
        $this->assertTrue($containerBuilder->has('davidbadura_fixtures.fixture_file_loader'));
    }

}