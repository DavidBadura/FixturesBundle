<?php

namespace DavidBadura\FixturesBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

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
            ->addOption('fixtures', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixtures from.', null)
            ->addOption('fixture-types', 't', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixture types from.', null)
            ->addOption('group', 'g', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixture types from.', null)
            ->addOption('no-persist', 'np', InputOption::VALUE_NONE, 'Test the fixtures.')
            ->addOption('no-validate', 'nv', InputOption::VALUE_NONE, 'Test the fixtures.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getContainer();

        $fileLoader = $container->get('davidbadura_fixtures.fixture_file_loader');
        $data = $fileLoader->loadFixtureData();

        $typeLoader = $container->get('davidbadura_fixtures.fixture_type_loader');
        $types = $typeLoader->load();

        $fixtureLoader = $this->getContainer()->get('davidbadura_fixtures.fixture_loader');

        $fixtureLoader->setLogger(function($message) use($output) {
            $output->writeln($message);
        });

        $fixtureLoader->loadFixtures($data, $types, array(
            'group' => $input->getOption('group'),
            'no-validate' => $input->getOption('fixture-types'),
            'no-persist' => $input->getOption('fixture-types')
        ));

    }
}
