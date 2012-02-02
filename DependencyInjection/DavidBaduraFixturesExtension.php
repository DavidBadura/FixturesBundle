<?php

namespace DavidBadura\FixturesBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\Definition\Processor;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class DavidBaduraFixturesExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);

        $loader->load('services.xml');

        $fixtureLoader = $container->get('davidbadura_fixtures.fixture_loader');

        foreach($config['persisters'] as $persisterConfig) {
            if($persisterConfig['type'] == 'doctrine') {
                $fixtureLoader->addPersister($persisterConfig['name'], new DoctrinePersister($persisterConfig['name'], $container->get($persisterConfig['service'])));
            } else {
                throw new \Exception(sprintf('persister type "%s" not exist', $persisterConfig['type']));
            }
        }

    }

}