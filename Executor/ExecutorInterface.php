<?php

namespace DavidBadura\FixturesBundle\Executor;

use DavidBadura\FixturesBundle\FixtureCollection;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
interface ExecutorInterface
{

    /**
     *
     * @param FixtureCollection $fixtures
     */
    public function execute(FixtureCollection $fixtures);
}
