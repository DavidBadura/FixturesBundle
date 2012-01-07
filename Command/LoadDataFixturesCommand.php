<?php

namespace DavidBadura\FixturesBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

use Symfony\Component\Finder\Finder;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use DavidBadura\FixturesBundle\Executor\Executor;
use DavidBadura\FixturesBundle\RelationManager\ORMRelationManager;
use DavidBadura\FixturesBundle\Persister\DoctrinePersister;
use DavidBadura\FixturesBundle\FixtureType\FixtureTypeLoader;
use Symfony\Component\Yaml\Yaml;
use DavidBadura\FixturesBundle\RelationManager\RelationManager;


/**
 * Load data fixtures from bundles.
 *
 * @author David Badura <d.badura@gmx.de>
 */
class LoadDataFixturesCommand  extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('davidbadura:fixtures:load')
            ->setDescription('Load data fixtures and save it.')
            ->addOption('fixtures', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixtures from.')
            ->addOption('fixture-types', 't', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixture types from.')
            ->addOption('test', null, InputOption::VALUE_NONE, 'Test the fixtures.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $output->writeln('load fixtures');
        
        $dirOrFile = $input->getOption('fixtures');
        if ($dirOrFile) {
            $paths = is_array($dirOrFile) ? $dirOrFile : array($dirOrFile);
        } else {
            $paths = array();
            foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
                $path = $bundle->getPath().'/Resources/fixtures';
                if(is_dir($path)) {
                    $paths[] = $path;
                }
            }
        }
        
        if(!$paths) {
            return;
        }
        
        $finder = new Finder();
        $finder->in($paths)->name('*.yml');
        
        $data = array();
        foreach ($finder->files() as $file) {
            
            $temp_data = Yaml::parse($file->getPathname());
            if(is_array($temp_data)) {
                $data = array_merge_recursive($data, $temp_data);
            }
            
        }

        $dirOrFile = $input->getOption('fixture-types');
        if ($dirOrFile) {
            $paths = is_array($dirOrFile) ? $dirOrFile : array($dirOrFile);
        } else {
            $paths = array();
            foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
                $paths[] = $bundle->getPath().'/FixtureTypes';
            }
        }

        $loader = new FixtureTypeLoader();
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            }
        }
        
        $fixtures = $loader->getFixtureTypes();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
            );
        }
        
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $relationManager = new ORMRelationManager($em);
        $relationManager = new RelationManager();
        $persister = new DoctrinePersister($em);
        $executor = new Executor($relationManager, $persister);
        $executor->setLogger(function($message) use ($output) {
            $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        
        foreach ($fixtures as $type) {
            $executor->addFixtureType($type);
        }
        
        $executor->execute($data, $input->getOption('test'));
    }
}
