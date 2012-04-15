<?php

namespace DavidBadura\FixturesBundle\FixtureConverter;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface ConverterDataValidate
{

    public function addNodeSchema(NodeBuilder $node);

}