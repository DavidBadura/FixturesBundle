<?php

namespace DavidBadura\FixturesBundle\Tests;

use DavidBadura\FixturesBundle\DavidBaduraFixturesBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use DavidBadura\FixturesBundle\DependencyInjection\DavidBaduraFixturesExtension;
use DavidBadura\FakerBundle\DependencyInjection\DavidBaduraFakerExtension;
use DavidBadura\FakerBundle\DavidBaduraFakerBundle;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DavidBaduraFixturesBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultBuild()
    {
        $container = new ContainerBuilder();
        $bundle = new DavidBaduraFixturesBundle();

        $bundle->build($container);

        $extension = new DavidBaduraFixturesExtension();
        $extension->load(array(), $container);

        $container->set('kernel', $this->getMock('Symfony\Component\HttpKernel\KernelInterface'));
        $container->set('event_dispatcher', $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface'));
        $container->set('validator', $this->getMock('Symfony\Component\Validator\ValidatorInterface'));
        $container->set('doctrine.orm.entity_manager', $this->getMock('Doctrine\Common\PersisterInterface'));

        $container->compile();

        $manager = $container->get('davidbadura_fixtures.fixture_manager');
        $this->assertInstanceOf('DavidBadura\FixturesBundle\FixtureManager', $manager);
    }

    public function testFakerBundleBuild()
    {
        $container = new ContainerBuilder();
        $bundle = new DavidBaduraFixturesBundle();
        $fakerBundle = new DavidBaduraFakerBundle();

        $bundle->build($container);
        $fakerBundle->build($container);


        $extension = new DavidBaduraFixturesExtension();
        $extension->load(array(
            'david_badura_fixtures' => array(
                'faker' => true
            )
        ), $container);

        $fakerExtension = new DavidBaduraFakerExtension();
        $fakerExtension->load(array(), $container);


        $container->set('kernel', $this->getMock('Symfony\Component\HttpKernel\KernelInterface'));
        $container->set('event_dispatcher', $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface'));
        $container->set('validator', $this->getMock('Symfony\Component\Validator\ValidatorInterface'));
        $container->set('doctrine.orm.entity_manager', $this->getMock('Doctrine\Common\PersisterInterface'));

        $container->compile();

        $manager = $container->get('davidbadura_fixtures.fixture_manager');
        $this->assertInstanceOf('DavidBadura\FixturesBundle\FixtureManager', $manager);


        $fakerListener = $container->get('davidbadura_fixtures.event_listener.faker');
        $this->assertInstanceOf('DavidBadura\FixturesBundle\EventListener\FakerListener', $fakerListener);
    }

}
