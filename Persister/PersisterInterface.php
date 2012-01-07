<?php

namespace DavidBadura\FixturesBundle\Persister;

/**
 * 
 * @author David Badura <d.badura@gmx.de>
 */
interface PersisterInterface
{

    /**
     * Save objects
     * 
     * @param array $objects
     */
    public function save($objects);
    
    
}