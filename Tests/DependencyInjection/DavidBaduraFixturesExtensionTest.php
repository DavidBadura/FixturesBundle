<?php

namespace DavidBadura\FixturesBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use DavidBadura\FixturesBundle\DependencyInjection\DavidBaduraFixturesExtension;

class DavidBaduraFixturesExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadDefaultServices()
    {
        $config = array();

        $containerBuilder = new ContainerBuilder();
        $loader = new DavidBaduraFixturesExtension();
        $loader->load($config, $containerBuilder);

        $this->assertTrue($containerBuilder->has('davidbadura_fixtures.fixture_manager'));
        $this->assertTrue($containerBuilder->has('davidbadura_fixtures.persister'));
        $this->assertTrue($containerBuilder->has('davidbadura_fixtures.converter.default'));
    }
}
