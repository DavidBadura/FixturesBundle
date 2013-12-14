<?php

namespace DavidBadura\FixturesBundle\Command;

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
class LoadDataFixturesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('davidbadura:fixtures:load')
            ->setDescription('Load data fixtures and save it.')
            ->addOption('fixture', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixtures from.', array())
            ->addOption('tag', 't', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Load fixtures by tag', array())
            ->addOption('dry_run', null, InputOption::VALUE_NONE, 'Test process (dont save fixtures)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $manager = $container->get('davidbadura_fixtures.fixture_manager');

        $manager->load($input->getOption('fixture'), array(
            'tags' => $input->getOption('tag'),
            'dry_run' => $input->getOption('dry_run')
        ));
    }

}
