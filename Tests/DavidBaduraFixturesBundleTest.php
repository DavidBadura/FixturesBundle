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
        $container->set('security.encoder_factory', $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface'));
        $container->set('doctrine.orm.entity_manager', $this->getMock('Doctrine\Common\Persistence\ObjectManager'));

        $container->compile();

        $manager = $container->get('davidbadura_fixtures.fixture_manager');
        $this->assertInstanceOf('DavidBadura\Fixtures\FixtureManager\FixtureManager', $manager);
    }

    public function testFakerBundleBuild()
    {
        $container = new ContainerBuilder();
        $bundle = new DavidBaduraFixturesBundle();
        $fakerBundle = new DavidBaduraFakerBundle();

        $bundle->build($container);
        $fakerBundle->build($container);
        
        $fakerExtension = new DavidBaduraFakerExtension();
        $fakerExtension->load(array(), $container);

        $extension = new DavidBaduraFixturesExtension();
        $extension->load(array(), $container);

        
        $container->set('kernel', $this->getMock('Symfony\Component\HttpKernel\KernelInterface'));
        $container->set('event_dispatcher', $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface'));
        $container->set('validator', $this->getMock('Symfony\Component\Validator\ValidatorInterface'));
        $container->set('security.encoder_factory', $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface'));
        $container->set('doctrine.orm.entity_manager', $this->getMock('Doctrine\Common\Persistence\ObjectManager'));

        $container->compile();

        $manager = $container->get('davidbadura_fixtures.fixture_manager');
        $this->assertInstanceOf('DavidBadura\Fixtures\FixtureManager\FixtureManagerInterface', $manager);

        $faker = $container->get('davidbadura_faker.faker');

        $this->assertTrue(is_numeric($faker->randomDigit));
        $this->assertTrue(is_numeric($faker->randomDigitNotNull));
        $this->assertTrue(is_numeric($faker->randomNumber));
        $this->assertTrue(is_numeric($faker->numerify));
    }

    public function testFunctional()
    {
        $container = new ContainerBuilder();
        $bundle = new DavidBaduraFixturesBundle();

        $bundle->build($container);

        $extension = new DavidBaduraFixturesExtension();
        $extension->load(array(
            'david_badura_fixtures' => array(
                'bundles' => array('DavidBaduraFixturesBundle')
            )
        ), $container);

        $bundle = $this->getMock('Symfony\Component\HttpKernel\Bundle\BundleInterface');
        $bundle
            ->expects($this->once())->method('getPath')
            ->will($this->returnValue(__DIR__))
        ;
           
        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel
            ->expects($this->once())->method('getBundle')
            ->with($this->equalTo('DavidBaduraFixturesBundle'))
            ->will($this->returnValue($bundle))
        ;

        $container->set('kernel', $kernel);

        $container->set('event_dispatcher', $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface'));
        $container->set('validator', $this->getMock('Symfony\Component\Validator\ValidatorInterface'));
        $container->set('security.encoder_factory', $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface'));

        $persister = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $persister->expects($this->exactly(6))->method('persist');
        $persister->expects($this->once())->method('flush');
        $container->set('doctrine.orm.entity_manager', $persister);

        $container->compile();

        $manager = $container->get('davidbadura_fixtures.fixture_manager');
        $manager->load();
    }

}
