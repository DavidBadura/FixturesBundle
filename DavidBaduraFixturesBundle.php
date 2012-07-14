<?php

namespace DavidBadura\FixturesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use DavidBadura\FixturesBundle\DependencyInjection\Compiler\ConverterPass;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class DavidBaduraFixturesBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConverterPass());
    }

}
