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

        if($config['persister'] == 'orm') {
                $persister = $container->register('davidbadura_fixtures.persister', 'DavidBadura\FixturesBundle\Persister\DoctrinePersister');
                $persister->addArgument(new Reference('doctrine.orm.entity_manager'));
        } else {
            throw new \Exception();
        }

        if(isset($config['bundles'])) {
            $loader = $container->getDefinition('davidbadura_fixtures.fixture_loader');
            $loader->addArgument($config['bundles']);

            $loader = $container->getDefinition('davidbadura_fixtures.converter_repository');
            $loader->addArgument($config['bundles']);
        }

    }

}