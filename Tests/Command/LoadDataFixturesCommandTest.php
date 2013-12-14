<?php

namespace DavidBadura\FixturesBundle\Tests\Command;

use DavidBadura\FixturesBundle\Command\LoadDataFixturesCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use DavidBadura\FixturesBundle\DavidBaduraFixturesBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use DavidBadura\FixturesBundle\DependencyInjection\DavidBaduraFixturesExtension;
use DavidBadura\FakerBundle\DependencyInjection\DavidBaduraFakerExtension;
use DavidBadura\FakerBundle\DavidBaduraFakerBundle;


/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class LoadDataFixturesCommandTest extends \PHPUnit_Framework_TestCase
{
    
    public function testCommandExecute()
    {
        $this->_testCommandExecute(false);
    }

    public function testCommandExecuteDryRun()
    {
        $this->_testCommandExecute(true);
    }

    private function _testCommandExecute($dryRun)
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

        $persister = $this->getMock('Doctrine\Common\Persistence\ObjectManager');

        if($dryRun) {
            $persister->expects($this->never())->method('persist');
            $persister->expects($this->never())->method('flush');
        } else {
            $persister->expects($this->exactly(6))->method('persist');
            $persister->expects($this->once())->method('flush');
        }
        
        $container->set('doctrine.orm.entity_manager', $persister);

        $container->compile();

        $command = new LoadDataFixturesCommand();
        $command->setContainer($container);    

        $command->run(new ArrayInput(array(
            '-f' => __DIR__ . '/../Resources/fixtures',
            '--dry_run' => $dryRun
        )), new NullOutput());
    }
}
